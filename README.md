# My Lovely Users List #

This plugin shows users list with corresponding details (fetched from 3rd party API *https://jsonplaceholder.typicode.com/*). Once clicked on the user from the list, his details are displayed in right column. The plugin is customizable and configurable.

## Installation

### Dependencies

No plugin is required to be installed prior to installing this plugin.

### Installation steps

1. Clone this repository in your plugins folder
2. Enter into folder *my-lovely-users-table* via shell or CMD and use [composer](https://getcomposer.org/) to install dependencies.

```bash
composer install
```
1. Login to WP admin area and Activate plugin *My Lovely Users List* 

## Usage

To access the plugin you can use the default endpoint 

```bash
/my-lovely-users-table/
```
or you can set your own endpoint in the admin area. Once you open mentioned url, you will be able to see the list of users retrieved from 3rd party API.

## Implementation

### Frontend technologies

jQuery is used to load the data dynamically on click. Styling is done via CSS and some responsiveness is also applied.

### Caching API requests

To reduce time needed for sequential requests, I used WordPress transients. The first time we send the request successfully, the response is stored in transient with expiry in one minute. You can change the expiry duration via options in the admin area.

### Error handling

If there is no response from the API server and the data is not cached plugin will show error message.

### AJAX loading the details

One clicked on any part of the user row in the table, the ID of that user will be reported and new request will be made to obtain user details. The request will also be cached as mentioned in previous section. I use nonce to prevent spam.

## Modifications/Customization

### Changing templates

You can copy the template file *my-lovely-users-table-public-display.php* from the plugin folder */public/partials* to your theme folder, or create folder */my-lovely-users-table/* in your theme and copy the file there. The plugin will first check if there exists file *my-lovely-users-table-public-display.php* in your theme folder and then if not, will show the file from plugin folder.

### Hooks

#### Actions

I created two actions, both accepting two parameters, that are the date, and user id.

- *my-lovely-users-table-user-clicked* for detecting when the user is displayed successfully.
- *my-lovely-users-table-user-error* for detecting when the user is not displayed.

Below is an example of code that is using these two actions to create two functions for loging clicks on user in a file. The code should be added in the functions.php file in the active WordPress theme.

    add_action('my-lovely-users-table-user-clicked','log_user_click', 10, 2);
    function log_user_click($date,$userid){
        $file = get_template_directory() . '/user-log.txt'; 
        $myfile = fopen($file, "a") or die("Unable to open file!");
        $txt = "Clicked on a user $userid at $date\n";
        fwrite($myfile, $txt);
        fclose($myfile);
    }

    add_action('my-lovely-users-table-user-error','log_user_click_error', 10, 2);
    function log_user_click_error($date,$userid){
        $file = get_template_directory() . '/user-log-error.txt'; 
        $myfile = fopen($file, "a") or die("Unable to open file!");
        $txt = "Clicked on a user at $userid $date\n";
        fwrite($myfile, $txt);
        fclose($myfile);
    }

Please make sure to update tests as appropriate.

## Unit tests

To be able to run unit tests you should run

```bash
./vendor/bin/phpunit
```

## License
[MIT](https://choosealicense.com/licenses/mit/)