# Freshbooks Time Entry to Slack

This repo was built for the specific use case where a single client/vendor of my services requires the details of my timesheet prior to invoicing. The client/vendor has a Slack team where I can post this information. The Freshbook client login allows clients to view the hours and projects for the time entries recorded, but it does not show the notes. This tool closes that gap by posting the project, date, hours, and notes to a Slack channel whenever a Freshbooks time entry for a project belonging to that client is created or edited.

## Requirements

* Freshbooks account
* Slack team
* Publically accessible web server
    * PHP (tested on 7.0, but I see no reason it shouldn't run on 5.3+)
    * Composer
    * Webserver needs write permissions to repo root (specifically to create and modify a file named post.log).
    
## Installation / Configuration

1. Clone the repo to your web server
1. run `composer install` from the repo root
1. Set up config.json
    1. Make a copy of, or rename config.example.json. The new file should be called config.json
    1. Freshbooks
        1. **domain**: This should be the first part of your Freshbooks URL, eg *example.freshbooks.com*
        1. **token**: You can create a Freshbooks API auth token in the My Account section of your Freshbooks site.
        1. **client_id**: This is the Freshbooks API id for the client you wish to post time entries for. You may not know this to begin with. You can retrieve this yourself using the Freshbooks API, or you can use the **list_clients** setting to discover this information as described below.
        1. **list_clients**: If this is set to true and the domain and token are properly configured, browsing to http://[path-to-repo-install]/clients.php, will display a list of your Freshbooks clients along with their API ids. Use this to discover the proper id for the step above.
        1. **post_log**: If this is set to true, all incoming post data will be logged in a post.log file in the repo root. For proper functionality, ensure that the webserver has write permissions do this directory/file. You may need to use this to validate your Freshbooks Webhook.
    1. Slack
        1. **webhook**: Create an incoming webhook by following [this link](https://my.slack.com/services/new/incoming-webhook/), and selecting the team and channel you wish to post to. Slack will provide you with a url, which you can paste to this config.
1. Create and verify a Freshbooks webhook. 
    1. Confirm that this repo is up and running at a public URL. You should be able to browse to the repo root/index.php and see the text "Config.json found".
    1. Ensure **post_log** is set to true in config.json.
    1. In the My Account section of your Freshbooks page create a new webhook using "time_entry" as the event and the public URL where this repo is installed as the URI.
    1. Upon creation of the webhook a verification code will be posted to the repo url. If **post_log** is set to true, this validation code will be recorded in post.log. You can submit this verification code along with it's id to the API [as described here](https://www.freshbooks.com/developers/webhooks) or you can click on the word "unverified" from your Freshbooks webhooks admin and paste it into the modal dialog.
1. Once the above steps are complete, create or modify a time entry for a project that belongs to the chosen client. If everythign is configured properly a message should be posted to the Slack channel of your choosing.
    
