api login/check needs a listener named AuthenticationSuccessListener (see doc: https://github.com/lexik/LexikJWTAuthenticationBundle/blob/3.x/Resources/doc/2-data-customization.rst#id7)
to send data user to the client App.

uploads/prescriptions and uploads/images folders are declared in the principal .gitignore file and contains a .gitignore file to create the folders when cloning on production and prevent cloning the images and prescriptions inside







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

    # to allow cors origin :
    in apache server it's important to choose in the request header an api name without special caracters including underscore ("apiKeyAuth" is OK but "api_key_auth" is NOT OK)
    insert allow-origin-cors in the .env with nelmio system
    And modify headers allow-origin-cors and allow-header-cors in the virtualhost or in the .htaccess
    Be careful!!! the dd() and dump() functions in the ApiClientAuthenticator may block the prod with cors messages
