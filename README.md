# Sleutelboer
#### Slackbot to publish webhooks from Topicus Keyhub.

*"I've got the key, I've got the secret. I've got the key to another way ~Urban Cookie Collective"*

![Sleutelboer](readme_assets/sleutelboer.png "Sleutelboer")



## Setup
- Clone repo
- ```composer install/update```
- Create Slack App and get App ID
- Set Bot Token Scope with [OAuth & Permissions](https://api.slack.com/apps/YOURAPPID/oauth)
    - channels:read
    - chat:write
    - groups:read
    - im:read
    - mpim:read
    - users:read
    - users:read:email
- Create DB and import sleutelboer.sql    
- Rename ```.env.example``` to ```.env``` and fill with your settings.
- Make cronjob to domain.tld/cron.php for user data updating.

## Functions
- Pipe webhooks from Keyhub to Slack.

Based on https://gist.github.com/wgroenewold/04c15c3a89455a55371f74bceef4e9a0