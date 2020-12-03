# Laravel Inertia Routes

When using Inertiajs in your Laravel project, you can render any component(svelte, vue or react) file using the Inertia::render method. But, what happens if don't need any backend data proccessing, just to render a component. You maybe ended up with something like this:

```php
Route::get('/your/custom/route', function(){
    Inertia::render('path/to/your/component');
});
```

and if you need multiple components? 💩

```php
Route::get('your/custom/route/home', function(){
    Inertia::render('path/to/component/home');
});
Route::get('your/custom/route/about', function(){
    Inertia::render('path/to/component/about');
});
Route::get('your/custom/route/contact', function(){
    Inertia::render('path/to/component/contact');
});
Route::get('your/custom/route/landing', function(){
    Inertia::render('path/to/component/landing');
});
```

This could be better! Introducing InertiaRoute, the simplest way to use your components as html pages:

```php
InertiaRoute::bind('your/custom/route/', 'components/folder/');
```

If you visit http://localhost:8000/your/custom/route/about it will render /resources/js/Pages/path/to/component/about.vue (or react or svelte). Now, all components that you create inside /resources/js/Pages/path/to/component/ will be automatically bind to a route like http://localhost:8000/your/custom/route/{component_name} 🤯.

Want to serve components from your root domain?

```php
InertiaRoute::bind('/', 'components/folder/');
```

**IMPORTANT!** InertiaRoute::bind use a "catch all" strategy, so put this route at the end of your route file or group.

## Installation

```
composer require jecovier/inertia-route
```

## What about parameters

If you want to use paramaters in your route( without any backend processing) you could use a route like this:

```php
InertiaRoute::get('/your/custom/route/{parameter}', 'path/to/your/component');
```

And your component will recevie that variable as a prop:

vue:

```js
<script>
export default{
    props: {
        parameter: { required:true }
    }
}
</script>
```

svelte:

```js
<script>export let parameter</script>
```

Reat:

```
Someone could help me with a react example? 🙊
```

## Middlewares... Prefixes... Can

InertiaRoute returns a Route object, so you can chained other route methods:

```php
InertiaRoute::get('/your/custom/route/{parameter}', 'path/to/your/component')
    ->middleware('validateParameter')
    ->can('viewThis');
```

## I am really lazy

```php
InertiaRoute::get('path/to/component/{parameter}');
InertiaRoute::bind('components/folder/');
```

This automatically bind the route your provide with the folder or component name. In this case:

```
http://localhost:8000/path/to/component/{parameter}
```

will be load:

```
/resources/js/Pages/path/to/component/{parameter}.vue (react or svelte)
```

... And for InertiaRoute::bind:

```
http://localhost:8000/components/folder/hola
```

will be load:

```
/resources/js/Pages/components/folder/hola.vue (react or svelte)
```

nice, right? 😎

## what if there is no component?

InertiaRoute verifies if the file exist before try to render it. So in case you want to access a non existing component, it will display a 404 page.

## I prefer Svelte or React

Yeah, me too (svelte simp here 🙊). By default, InertiaRoute will be render Vue files, so in most cases you don't need to do anything. But, if you are working with React or Svelte, you could easily switch to those frameworks.

In case of **React**:

```php
InertiaRoute::react();
InertiaRoute::get('path/to/component/{parameter}');
```

will be load:

```
/resources/js/Pages/path/to/component/{parameter}.js
```

And for **Svelte**:

```php
InertiaRoute::svelte();
InertiaRoute::get('path/to/component/{parameter}');
```

will be load:

```
/resources/js/Pages/path/to/component/{parameter}.svelte
```

## Other Verbs?

I don't know why you want to do that 🤷... but you can!... just define your routes like:

```php
InertiaRoute::get('/your/custom/route/{parameter}');
InertiaRoute::post('/your/custom/route/{parameter}');
InertiaRoute::put('/your/custom/route/{parameter}');
InertiaRoute::patch('/your/custom/route/{parameter}');
InertiaRoute::delete('/your/custom/route/{parameter}');
```

## I don't use /resources/js/Pages

Inertiajs suggests this folder in its installation guide, however, you may be use another folder. So If that is the case, you can change your root folder with:

```php
InertiaRoute::root('your/new/path');
```

Consider that InertiaRoute automatically use the laravel folder as root, so the final path will be: /laravel-project-folder/your/new/path/

## Contribution

Yes, please. This is a little library make in my spare time, so any PR is welcome 🙌.

## License

MIT
