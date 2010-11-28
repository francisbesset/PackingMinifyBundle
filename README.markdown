PackingMinifyBundle, Enhanced Stylesheets and Javascripts for Symfony2
=================================================================

PackingMinifyBundle merge your stylesheets files and javascripts files.
The files merged can be minify.

## Installation

### Add PackingMinifyBundle to your src/Bundle dir

    git submodule add git://github.com/francisbesset/PackingMinifyBundle.git src/Bundle/PackingMinifyBundle

### Add I18nRoutingBundle to your application kernel

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

#### Add config

    // app/config/config.yml
    packingMinify.config:
        css: { minify: true }
        js:  { minify: true }

#### Add routing

    // app/config/routing.yml
    _packing_minify:
        resource: PackingMinifyBundle/Resources/config/routing/packing_minify.xml
        prefix:   /_pm

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
