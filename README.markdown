PackingMinifyBundle, Enhanced Stylesheets and Javascripts for Symfony2
=================================================================

PackingMinifyBundle merge your stylesheets files and javascripts files.
The files merged can be minify.

## Installation

### Add PackingMinifyBundle to your src/Bundle dir

    git submodule add git://github.com/francisbesset/PackingMinifyBundle.git src/Bundle/PackingMinifyBundle

### Add PackingMinifyBundle to your application kernel

    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new Bundle\PackingMinifyBundle\PackingMinifyBundle(),
            // ...
        );
    }

### Update your config

#### Add routing

    // app/config/routing.yml
    _packing_minify:
        resource: PackingMinifyBundle/Resources/config/routing/packing_minify.xml
        prefix:   /_pm

#### Add config

    // app/config/config.yml
    packingMinify.config:
        css: { minify: true }
        js:  { minify: true }

#### Advanced config

    // app/config/config.yml
    packingMinify.config:
        css:
            minify:   true
            minifier: basic
        js:
            minify:   true
            minifier: packer
            options:
                encoding:      "Normal"
                fast_decode:   true
                special_chars: false

By default, the minifier for your stylesheets is Basic. There are no other minifier available for your stylesheets.
By default, the minifier for your javascripts is JSMin. There are also minifier packer who takes the above options.
Packer available options:
  encoding: None, Numeric, Normal, High ASCII
  fast_decode: Boolean
  special_chars: Boolean

## Use

To use PackingMinifyBundle, call the classic method to add a stylesheet or javascript.

### Twig

    {% javascript 'js/jquery/jquery.js' %}
    {% javascript 'js/main.js' %}
    {% stylesheet 'css/main.css' %}
    {% stylesheet 'css/form.css' %}
    
    {% javascripts %}
    {% stylesheets %}

### PHP

    <?php $view['javascripts']->add('js/jquery/jquery.js') ?>
    <?php $view['javascripts']->add('js/main.js') ?>
    <?php $view['stylesheets']->add('css/main.css') ?>
    <?php $view['stylesheets']->add('css/form.css' ?>
    
    <?php echo $view['javascripts'] ?>
    <?php echo $view['stylesheets'] ?>
