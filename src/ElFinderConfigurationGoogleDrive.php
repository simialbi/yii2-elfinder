<?php

namespace simialbi\yii2\elfinder;

class ElFinderConfigurationGoogleDrive extends ElFinderConfiguration
{
    /**
     * @var string Your Google Cloud client ID found in https://developers.google.com/console
     */
    public string $client_id;

    /**
     * @var string Your Google Cloud client secret found in https://developers.google.com/console
     */
    public string $client_secret;

    /**
     * @var array The access token
     */
    public array $access_token = [];

    /**
     * @var string The refresh token
     */
    public string $refresh_token;

    /**
     * @var string If you want to use an account configuration file
     */
    public string $serviceAccountConfigFile;

    /**
     * @var string The display name for the root
     */
    public string $root = 'My Drive';

    /**
     * @var string Define the alias used for files and folders. %s will be replaced by the real file / folder name
     */
    public string $gdAlias = '%s@GDrive';

    /**
     * @var string The google api client class file
     */
    public string $googleApiClient;

    /**
     * @var bool Use the google drive thumbnails instead of generating ones.
     */
    public bool $useGoogleTmb = true;

    /**
     * @inheritdoc
     */
    public $acceptedName = '#.#';

    /**
     * @var string The root css class
     */
    public string $rootCssClass = 'elfinder-navbar-root-googledrive';

    /**
     * @var array The permissions to set
     * @see https://developers.google.com/drive/api/reference/rest/v3/permissions
     */
    public array $publishPermission = [
        'type' => 'anyone',
        'role' => 'reader',
        'withLink' => true
    ];

    /**
     * @var array|string[] Google docs mime type mapping
     */
    public array $appsExportMap = [
        'application/vnd.google-apps.document' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.google-apps.spreadsheet' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.google-apps.drawing' => 'application/pdf',
        'application/vnd.google-apps.presentation' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/vnd.google-apps.script' => 'application/vnd.google-apps.script+json',
        'default' => 'application/pdf'
    ];
}
