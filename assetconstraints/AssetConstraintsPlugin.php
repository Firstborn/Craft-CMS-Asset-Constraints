<?php
namespace Craft;

class AssetConstraintsPlugin extends BasePlugin
{
    public function getName()
    {
        return Craft::t('Asset Constraints');
    }

    public function getDescription()
    {
        return 'Set constraints for asset uploads based on file type and asset folder.';
    }

    public function getVersion()
    {
        return '1.0.0';
    }

    public function getDeveloper()
    {
        return 'Firstborn';
    }

    public function getDeveloperUrl()
    {
        return 'http://www.firstborn.com';
    }

    protected function defineSettings()
    {
        /*
         * array(
                    'type' => '*',
                    'source' => '*',
                    'maximum_size' => ''
                )
         */
      return array(
            'constraints' => array(AttributeType::Mixed, 'default' => array()),
        );
    }

    public function getSettingsHtml()
    {
        $sources = craft()->assetSources->getAllSources();
        $extensions = IOHelper::getAllowedFileExtensions();
        $settings = $this->getSettings();
        $constraints = [];

        foreach($settings['constraints'] as $constraint){
            $constraints[] = [$constraint['source'], $constraint['type'], $constraint['maximum_size']];
        }

        $configConstraints = craft()->config->get('constraints', 'assetconstraints');
        $settings['constraints'] = $constraints;
        return craft()->templates->render('assetconstraints/settings', array(
            'settings' => $settings,
            'config' => $configConstraints,
            'sources' => $sources,
            'extensions' => $extensions
        ));
    }

    public function prepSettings($settings)
    {
        $constraints = [];
        if (isset($settings['constraints']) && is_array($settings['constraints']))
        foreach ($settings['constraints'] as $constraint){
            $constraints[] = [
                'source' => $constraint[0],
                'type' => $constraint[1],
                'maximum_size' => $constraint[2]
            ];

        }
        $settings['constraints'] = $constraints;
        return $settings;
    }

    public function findConstraints($sourceId, $ext)
    {
        $settings = $this->getSettings();

        $settingsConstraints = $settings['constraints'];
        $configConstraints = craft()->config->get('constraints', 'assetconstraints');
        $constraints = array_merge($configConstraints, $settingsConstraints);

        $matches = [];
        foreach($constraints as $constraint){
            if (($constraint['source'] == $sourceId || $constraint['source'] == '*') && ($constraint['type'] == $ext || $constraint['type'] == '*')){
                AssetConstraintsPlugin::log('match ' . json_encode($constraint), LogLevel::Error);
                $matches[] = $constraint;
            }
        }
        return $matches;

    }

    private function testConstraints($constraints, $attributes)
    {
        $pass = true;
        foreach($constraints as $constraint) {
            if ($attributes['size'] > $constraint['maximum_size']) {
                $pass = false;
                AssetConstraintsPlugin::log('Constraint failed: source_id: '. $constraint['source'] . ' maximum size: ' . $constraint['maximum_size'] . ' file: ' . $attributes['file'] . ' size: ' . $attributes['size'] , LogLevel::Error);
            }
        }
        return $pass;
    }

    public function init()
    {
        craft()->on('assets.onBeforeUploadAsset', function(Event $event) {
            $path = $event->params['path'];
            $fileSize = IOHelper::getFileSize($path);
            $fileSize = ceil($fileSize/1024); //convert to kb
            $ext = IOHelper::getExtension($path);
            $destination = $event->params['folder'];
            $constraints = $this->findConstraints($destination->id, $ext);
            $pass = $this->testConstraints($constraints, array('file' => $event->params['filename'], 'size' => $fileSize));
            if (!$pass){
                $event->performAction = false;
            }
        });



    }


}