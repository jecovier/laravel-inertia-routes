# Laravel Inertia Routes

```php
/**
 * /resources/js/Pages/folder/component.vue
 * http://your-domain.com/folder/component
 */
InertiaRoute::get('folder/component');

/**
 * Change route:
 * /resources/js/Pages/folder/component.vue
 * http://your-domain.com/about
 */
InertiaRoute::get('about', 'folder/component');

/**
 * Automatically:
 * /resources/js/Pages/folder/component.vue
 * http://your-domain.com/folder/component
 */
InertiaRoute::bind('folder');

// change framework
InertiaRoute::vue();
InertiaRoute::react();
InertiaRoute::svelte();
```

## Installation

```
composer require jecovier/laravel-inertia-route
```

## Usage

## Single component

```php
/**
 * /resources/js/Pages/folder/component.vue
 * http://your-domain.com/folder/component
 */
InertiaRoute::get('folder/component');

/**
 * Change route:
 * /resources/js/Pages/folder/component.vue
 * http://your-domain.com/about
 */
InertiaRoute::get('about', 'folder/component');
InertiaRoute::get('/', 'folder/component');
```

Also you can use other verbs:

```php
InertiaRoute::get('folder/component');
InertiaRoute::post('folder/component');
InertiaRoute::put('folder/component');
InertiaRoute::delete('folder/component');
InertiaRoute::patch('folder/component');
```

## Bind folder

Bind all components inside a folder:

```php
InertiaRoute::bind('some/folder/');
```

If you have components like:

```
resources/js/Pages/components/folder/index.vue
resources/js/Pages/components/folder/about.vue
resources/js/Pages/components/folder/anotherPage.vue
```

Now you can access them using:

```
http://yourdomain.com/some/folder/
http://yourdomain.com/some/folder/about
http://yourdomain.com/some/folder/anotherPage
```

You can change routes for folders using:

```php
InertiaRoute::bind('you/route', 'some/folder/');
```

**IMPORTANT!** InertiaRoute::bind use a "catch all" strategy, so put this route at the end of your route file or group.

### Parameters

If you want to use route paramaters(without any backend proccess) you could define a route like this:

```php
InertiaRoute::get('/route/{name}', 'folder/component');
```

And your component will receive parameters as props:

vue:

```js
<script>
export default{
    props: {
        name: { required:true }
    }
}
</script>
```

svelte:

```js
<script>export let name</script>
```

Reat:

```
// I don't know react :(
```

Also you can use:

```php
InertiaRoute::get('folder/component/{name}');
```

## Middlewares... Prefixes... Can...

InertiaRoute returns a Route object instance, so you can chained other methods:

```php
InertiaRoute::get('/route/{parameter}', 'folder/component')
    ->middleware('validateParameter')
    ->can('viewThis');
```

## 404

InertiaRoute verifies if the file exist before try to render it. In case you want to access a non existing component, it will display a 404 page.

## Svelte or React

By default InertiaRoute will render Vue files. But, if you are working with React or Svelte, you could easily switch to those frameworks.

In case of **React**:

```php
InertiaRoute::react();
InertiaRoute::get('path/to/component/{parameter}');
```

And for **Svelte**:

```php
InertiaRoute::svelte();
InertiaRoute::get('path/to/component/{parameter}');
```

Remember to change framework before declare routes.

## Change root path

Inertiajs suggests resources/js/Pages as default folder, however, you may be use another path. If that is the case, you can change your root folder with:

```php
InertiaRoute::root('your/new/path');
```

Consider that InertiaRoute automatically use the laravel folder as root, so the final path will be: /laravel-project-folder/your/new/path/

## Contribution

Yes, please. This is a little library make in my spare time, any PR is welcome 🙌.

## License

MIT
