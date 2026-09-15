# Task i - Tasks index (search + filter + sort + pagination)

**Blade way:** TaskController@index reads request('q'), request('status'), request('sort'), builds the query, returns the view. The blade file has a form that submits on "Filter" click and reloads the whole page. URL params are kept across pagination with withQueryString().

**Livewire way:** One file, resources/views/pages/tasks/index.blade.php (Livewire single-file component), has the PHP class and the template together. The three filters are just public properties with #[Url(as: 'q')] etc on them, and the inputs use wire:model.live so typing/selecting fires an update automatically - no submit button, no reload. Livewire keeps the URL in sync on its own because of the #[Url] attribute.

**What's shared (didn't duplicate anything):** the actual query logic - Task::search()->status()->ordered() - is called exactly the same way in both. Same scopes, same paginate(10), same Tailwind markup basically copy-pasted for the cards and empty states.

**What Livewire actually replaces:** the whole "submit form -> reload page -> controller reads request()" cycle is gone. It's just "type -> background request -> DOM updates" instead.

**Annoying stuff I ran into:**
- Livewire won't let a component have more than one root HTML element. My first version had 3 separate divs at the top level and it just 500'd until I wrapped everything in one outer div.
- The default layout Livewire tries to use didn't work with my existing layout because my app.blade.php uses @yield('content') and Livewire wants {{ $slot }}. Had to make a whole separate layouts/livewire.blade.php file instead of touching the real one.
- Weirdest bug: the URL wasn't updating when I changed filters, no errors, nothing. Turned out my app.js manually starts Alpine.js, and Livewire ALSO starts its own Alpine internally, so I had two Alpines fighting each other and it broke the URL syncing silently. Fixed it by not loading app.js at all on the Livewire layout, just the CSS - Livewire's own Alpine handles everything on those pages now.

# Task ii - Create/edit form

**Blade way:** TaskController@store/@update validate the request through StoreTaskRequest/UpdateTaskRequest form requests, which both use the shared HasTaskRules trait for rules(). Errors flow through Laravel's normal $errors bag and show via @error() in the shared _form.blade.php partial. On success it redirects with session()->flash and a toast shows on the next page.

**Livewire way:** Two separate single-file components, create and edit. Both `use HasTaskRules` directly (the same trait, just used straight in the component instead of through a FormRequest) and implement a rules() method that calls $this->taskRules(). wire:submit="save" calls the save() method, which calls $this->validate() - populates the exact same $errors bag Blade uses, so @error() in the template works identically with zero changes.

**What's shared:** the HasTaskRules trait itself - didn't duplicate a single validation rule. Also reused the exact Tailwind markup for the form fields.

**What Livewire replaces:** FormRequest classes aren't used at all here - validation lives directly on the component via rules(). The controller's redirect()->with('success', ...) pattern is basically identical though, just called from inside the component's save() method instead.

**Edit specifically:** used mount(Task $task) for route-model binding (same idea as type-hinting Task $task in a controller method) and called $this->authorize('update', $task) to run the exact same TaskPolicy check Blade uses - not a new policy, the same one.

**Things I noticed:**
- Had to click "Create task" with everything empty to actually test validation - the first time I tried I accidentally typed something in the title so it just made a junk task instead of testing the empty case.
- Livewire's validate() error messages look identical to Blade's since they come from the same Laravel validation language files - no visual difference at all in the @error() output.

# Task iii - Show + toggle + delete

**Blade way:** show.blade.php just displays the task. Toggle is a tiny <form method=POST> with @method(PATCH) that submits to TaskController@toggle. Delete is another form with @method(DELETE) and a plain JS `onclick="return confirm(...)"` before it submits. Both go through TaskPolicy via the route model binding + authorize() calls inside the controller/FormRequest.

**Livewire way:** One component, mount(Task $task) does $this->authorize(view, $task) up front (so even loading the page 404s if you don't own it). Toggle and delete are just wire:click="toggle" and wire:click="delete" on plain buttons - no forms needed at all. Delete also has wire:confirm="..." right on the button which pops the browser's native confirm dialog automatically before calling delete() - no manual JS.

**What's shared:** TaskPolicy - authorize('view', ...), authorize('update', ...), authorize('delete', ...) are the exact same policy methods the Blade side uses. Wrote zero new authorization logic.

**What Livewire replaces:** the two separate <form> + hidden _method field + submit combo for toggle/delete are gone, it's just buttons with wire:click. The manual `onclick="return confirm()"` JS is gone too - wire:confirm does it declaratively in the HTML.

**Nice surprise:** wire:confirm works with zero JS I had to write - I expected to need some x-data/Alpine setup like the delete confirm on my Blade version probably needed originally, but it's literally just an attribute.

# Task iv - Auth (register/login/logout + password reset)

**Blade way:** AuthController handles register/login/logout, each using a FormRequest (RegisterRequest/LoginRequest) for validation. Password reset is a separate PasswordResetController using Password::sendResetLink() / Password::reset(). All behind guest/auth route middleware groups in web.php.

**Livewire way:** Four components - register, login, forgot-password, reset-password - each `use`ing the exact same validation rules as their FormRequest counterparts (copied the rules() array directly since Livewire components don't use FormRequest classes at all). wire:submit calls the action method, same Auth::attempt()/Auth::login()/Password::sendResetLink()/Password::reset() calls as the controllers. Routes sit in their own guest-middleware Livewire group, same idea as the Blade guest group.

**Logout specifically:** didn't build a Livewire component for this at all - it's just a plain <form method=POST> in the Livewire layout posting straight to the existing AuthController@logout route. Not everything needs to be a component; logout has no interactive state so a normal form is simpler and still works fine on a Livewire page.

**What's shared:** the actual FormRequest validation rules arrays, copied field-for-field. Auth::attempt(), Auth::login(), Hash::make(), Password::sendResetLink(), Password::reset() - identical calls to what the controllers already do. TaskPolicy isn't touched here since it's not needed for auth, but same principle as tasks - reuse everything possible, don't rewrite business logic.

**Two real bugs I hit and had to actually understand, not just copy-paste around:**

1. Password reset links kept going to the Blade /reset-password/... URL even when triggered from my Livewire forgot-password page. Turns out Laravel's built-in ResetPassword notification always builds its link by calling route('password.reset', ...) - a hardcoded literal route name inside Laravel's own framework code. Since my Livewire route was named livewire.password.reset (different name), it never got picked, and the Blade route (literally named password.reset) won every time regardless of which page sent the email. Fixed it by calling ResetPassword::createUrlUsing(...) right before Password::sendResetLink() inside my component only, pointing it at the livewire.password.reset route, then immediately setting it back to null after - so this override only affects this one call and never leaks into the Blade flow.

2. My reset-password component had a method called reset() and Livewire's base Component class already HAS a method called reset() (used internally for resetting properties to defaults). Declaring my own broke with a "must be compatible with Livewire\Component::reset()" fatal error. Renamed mine to resetPassword() and it worked immediately. Lesson: don't use mount, render, or reset as your own method names in a Livewire component.
