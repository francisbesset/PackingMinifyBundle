DynamicsBundle, Enhanced Stylesheets and Javascripts for Symfony2
=================================================================

DynamicsBundle merge your stylesheets files and javascripts files.
The files merged can be minify.

## Installation

### Add DynamicsBundle to your src/Bundle dir

    git submodule add git://github.com/francisbesset/DynamicsBundle.git src/Bundle/DynamicsBundle

### Add I18nRoutingBundle to your application kernel

    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new Bundle\DynamicsBundle\DynamicsBundle(),
            // ...
        );
    }

### Update your config

#### Add config

    // app/config/config.yml
    dynamics.config:
        css: { minify: true }
        js:  { minify: true }

#### Add routing

    // app/config/routing.yml
    _dynamics:
        resource: DynamicsBundle/Resources/config/routing/dynamics.xml
        prefix:   /_dynamics

## Use

To use DynamicsBundle, call the normal method to add a stylesheet or javascript.

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
