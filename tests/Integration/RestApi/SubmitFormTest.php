<?php


namespace calderawp\calderaforms\Tests\Integration\RestApi;



use calderawp\calderaforms\cf2\RestApi\Process\Submission;

class SubmitFormTest extends RestApiTestCase
{
	/**
	 * @var string
	 */
	protected $test_file;

	/** @inheritdoc */
	public function setUp()
	{
		parent::setUp(); // TODO: Change the autogenerated stub
	}

	/**
	 * @covers \calderawp\calderaforms\cf2\RestApi\Submission::getArgs()
	 * @covers \calderawp\calderaforms\cf2\RestApi\Submission::add_routes()
	 * @covers \calderawp\calderaforms\cf2\RestApi\Register::initEndpoints()
	 * @covers \calderawp\calderaforms\cf2\RestApi\Submission::getNamespace()
	 *
	 * @since 1.9.0
	 *
	 * @group cf2
	 */
	public function testRouteCanBeRequest()
	{
		$request = new \WP_REST_Request('GET', '/cf-api/v3');
		$response = rest_get_server()->dispatch($request);
		$this->assertTrue(
			array_key_exists('/cf-api/v3' . Submission::URI, $response->get_data()[ 'routes' ])
		);
		$this->assertTrue(
			in_array('POST', $response->get_data()[ 'routes' ][ '/cf-api/v3/file' ][ 'methods' ])
		);

	}

	/**
	 * Test we can create entries
	 *
	 * @covers \calderawp\calderaforms\cf2\RestApi\File\CreateFile::createItem()
	 *
	 * @since 1.8.0
	 *
	 * @group cf2
	 * @group file
	 * @group field
	 * @group cf2_file
	 */
	public function testCreateItem()
	{
		$formId = caldera_forms_tests_get_simple_form_with_just_a_text_field_form_id();

		$route = new Submission();
		$valueOfTextField = 'Value of text field';
		$data = [
			'text_field' => $valueOfTextField
		];
		$request = new \WP_REST_Request();
		$request->set_param('formId', $formId);
		$request->set_param(Submission::VERIFY_FIELD, \Caldera_Forms_Render_Nonce::create_verify_nonce($formId));
		$request->set_param('entryData', $data);

		wp_set_current_user(1);

		try {
			$response = $route->createItem($request);
		} catch (\Exception $e) {

		}

		$this->assertEquals(201, $response->get_status());
		$data = $response->get_data();
		$this->assertArrayHasKey('entryId', $data);
		$entryId = $data[ 'entryId' ];
		$this->assertIsNumeric($entryId);
		$form = \Caldera_Forms_Forms::get_form($formId);
		$fieldValue = \Caldera_Forms::get_field_data( 'test_field',$form,$entryId );

		$this->assertEquals( $data['text_field'], $fieldValue );

	}

}
