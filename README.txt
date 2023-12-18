










## upload in distant server:

    # First: to make git pull requests
    git clone https://github.com/…..
    git remote

    # setting up the .env.local file:
    modify .env.local:
        -database
        -mailer : MAILER_DSN=sendmail://default ou MAILER_DSN=smtp://localhost?verify_peer=0

    # to start the website:
    composer install
    composer require symphony/apache-pack


