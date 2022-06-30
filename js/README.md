# Phlex UI js package

The javascript package is necessary to run Phlex UI. It provide necessary
jQuery plugin needed for Phlex\Ui and also provide app wide services for semantic-ui module.

The package also export some functions via the phlex global object.

### Getting package version

Calling this function in your custom js script or in console will output the package version number.
```
    phlex.version();
```

### Services 

All services are export via the phlex global object. You can access them via phlex.serviceName.
Certain functionalities are offered from these services. 

For example, if one of your script need to send an ajax request directly, without using semantic-ui api request, you could use the apiService.phlexSuccessTest
to run and evaluate the server response from Phlex\Ui.

```
    $.getJSON( "myajax.php", function( resp ) {
      phlex.apiService.phlexSuccessTest(resp);
     });

```

Another example would be the upload service for file uploading using one of your script.

```
    phlex.uploadService.uploadFiles(
      filesArraytoUpload,
      theElement,
      {data: 'value'},
      url,             
      onComplete(){}, // the callback function when upload is complete.
      onXhr(){}       // the callback function when uploading files is in progress.
    );
```

### jQuery plugin

The phlex global object may be used as a quick way of registering a jQuery plugin under the phlex namespace.

Let's create a new jQuery plugin that will change every selected dom element text color to green.

```
    phlex.registerPlugin('Greenify', function(el) {
        $(el).css("color", "green");
    })
```

The plugin can now by invoke using:

```
    // Change all link color text to green.
    $('a').phlexGreenify();
```

## Developping and building package.

You may change this package to suit your own needs.

### Package installation

First start by installing the package using npm install. 

```
    cd phlex-ui/js
    npm install
```

### Development

For development and debugging, simply use the "dev" script supply in package.json file by running this command:

```
    npm run dev
```

This command will output the phlex-ui.js file inside the /public directory including the .map file need for debugging
the package. Once load in your page, code can be debugged in browser from the webpack source.

Any change made to the source, will also be re-compile automatically when using the "dev" script.

#### Analyzing bundle profile

Bundle profile may be analyze using various tools. npm script are availabe for producing 
the json file for this. 

```
    npm run profile
```

This command will create a profile json file `phlex-bundle-profile.json` with bundle information inside the profile folder. You can use this file with your 
favorite bundle analyzer. 

Another npm script is available for analyzing the bundle using the webpack-bundle-analyzer tool.

```
    npm run analyze-profile
```

Note: In order to use this script, make sure that the webpack-bundle-analyzer package is intall
globally.

```
    npm install -g webpack-bundle-analyzer
```

### Production

For production, simply use the "build" script supply in package.json.

```
    npm run build
```

This command will output the phlex-ui.min.js file, also in /public folder.
