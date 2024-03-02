# 👷‍♀️ Contributing

1. Clone this repository to your device

2. Inside the root of this repository run `composer install`

3. Create a test project in which you will use this package (follow the [Usage instructions in the README.md](README.md#🚀-using-this-package))

4. Add the package locally using the following additions to your composer.json:

    ```json
    "repositories": [
        {
            "type": "path",
            "url": "../sdclient"
        }
    ],
    ```

    __Note:__ `../sdclient` should point to where you cloned this package, relative to the test project.

5. Run `composer require "curio/sdclient @dev"` inside the test project

You can now test and modify this package. Changes will immediately be reflected in the test project.
