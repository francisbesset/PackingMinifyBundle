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
        resource: @PackingMinifyBundle/Resources/config/routing/packing_minify.xml
        prefix:   /_pm

#### Update config

##### Add
    // app/config/config.yml
    packing_minify.config:
        css: { minify: true }
        js:  { minify: true }

##### Delete
    // app/config/config.yml
    imports:
        - { resource: @CompatAssetsBundle/Resources/config/assets.xml }

#### Advanced config

    // app/config/config.yml
    packing_minify.config:
        css:
            minify:   true
            minifier: cssmin
            options:
                remove-empty-blocks:     true
                remove-empty-rulesets:   true
                remove-last-semicolons:  true
                convert-css3-properties: false
                convert-color-values:    false
                compress-color-values:   false
                compress-unit-values:    false
                emulate-css3-variables:  true
        js:
            minify:   true
            minifier: packer
            options:
                encoding:      "Normal"
                fast_decode:   true
                special_chars: false

### Minifiers

#### Stylesheets

* **Basic**: No option available (minifier by default)  
* **CSSMin**:  
 remove-empty-blocks:     Boolean  
 remove-empty-rulesets:   Boolean  
 remove-last-semicolons:  Boolean  
 convert-css3-properties: Boolean  
 convert-color-values:    Boolean  
 compress-color-values:   Boolean  
 compress-unit-values:    Boolean  
 emulate-css3-variables:  Boolean  

#### Javascripts

* **JSMin**: No option available (minifier by default)  
* **Packer**:  
 encoding:      None, Numeric, Normal, High ASCII  
 fast_decode:   Boolean  
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
    <?php $view['stylesheets']->add('css/form.css') ?>
    
    <?php echo $view['javascripts'] ?>
    <?php echo $view['stylesheets'] ?>
