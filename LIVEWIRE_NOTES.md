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
