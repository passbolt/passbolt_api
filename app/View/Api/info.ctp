<?php
// Swagger config

echo "<?php";?>

/**
 * API Documentation
 *
 * @SWG\Swagger(
 *     schemes={"http<?php if(Configure::read('force_ssl')) echo 's'; ?>"},
 *     host="<?php echo Router::url('/',true); ?>",
 *     basePath="/",
 *     @SWG\Info(
 *         version="<?php echo Configure::read('App.version.number'); ?>",
 *         title="<?php echo Configure::read('App.name'); ?> JSON API",
 *         description="<?php echo Configure::read('punchline'); ?>",
 *         termsOfService="https://www.passbolt.com/terms/",
 *         @SWG\Contact(
 *             email="contact@passbolt.com"
 *         ),
 *         @SWG\License(
 *             name="AGPL-v3",
 *             url="http://www.gnu.org/licenses/agpl-3.0.en.html"
 *         )
 *     ),
 *     @SWG\ExternalDocumentation(
 *         description="Find out more about Passbolt",
 *         url="https://passbolt.com/help"
 *     )
 * )
 */
<?php echo "?>"; ?>
