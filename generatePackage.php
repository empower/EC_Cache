<?php

error_reporting(E_ALL & ~E_DEPRECATED);

require_once('PEAR/PackageFileManager2.php');

PEAR::setErrorHandling(PEAR_ERROR_DIE);

$packagexml = new PEAR_PackageFileManager2;

$packagexml->setOptions(array(
    'baseinstalldir'    => '/',
    'simpleoutput'      => true,
    'packagedirectory'  => './',
    'filelistgenerator' => 'file',
    'ignore'            => array('phpunit-bootstrap.php', 'phpunit.xml', 'generatePackage.php', 'tests/results/'),
    'dir_roles' => array(
        'tests'    => 'test',
    ),
    'exceptions' => array(
        'INSTALL'    => 'doc',
        'README.mkd' => 'doc',
        'LICENSE'    => 'doc',
    ),
));

$packagexml->setPackage('EC_Cache');
$packagexml->setSummary('Multi get/set support for Zend_Cache, as well as proper mock backend for use with testing');
$packagexml->setDescription(
    'Supports loadMulti() and saveMulti() interface.  '
    . 'And with the mock backend, all items are stored in the backend instance (memory).  Allows for proper mocking.'
);

$packagexml->setChannel('empower.github.com/pirum');
$packagexml->setAPIVersion('0.1.3');
$packagexml->setReleaseVersion('0.1.3');

$packagexml->setReleaseStability('alpha');

$packagexml->setAPIStability('alpha');

$packagexml->setNotes('
* Fixed ZF channel dependency
');
$packagexml->setPackageType('php');
$packagexml->addRelease();

$packagexml->detectDependencies();

$packagexml->addMaintainer('lead',
                           'shupp',
                           'Bill Shupp',
                           'bshupp@empowercampaigns.com');
$packagexml->addMaintainer('lead',
                           'dcopeland',
                           'Dan Copeland',
                           'dcopeland@empowercampaigns.com');
$packagexml->setLicense('New BSD License',
                        'http://www.opensource.org/licenses/bsd-license.php');

$packagexml->setPhpDep('5.0.0');
$packagexml->setPearinstallerDep('1.4.0b1');
$packagexml->addPackageDepWithChannel('required', 'zf', 'pear.zfcampus.org', '1.11.10');

$packagexml->generateContents();
$packagexml->writePackageFile();

?>
