Feature:
  In order to create a Foo
  As an API user
  I want to test that Foo can be created

  Scenario: It can create a Foo
    Given the queue associated to transport kafka is empty
    When A POST request is sent to "/v1/foo" with JSON body:
      """
       {
          "id": "9cc900eb-663a-4292-876d-5a77eeefade9",
          "name": "Some foo name"
       }
       """
    Then the response header "Content-Type" should be equal to "application/json"
    And the response header "Location" should be equal to "http://localhost/v1/foo/9cc900eb-663a-4292-876d-5a77eeefade9"
    And the response should be empty
    And the response code should be 202
    And the response should be a documented and validated with OpenApi schema POST "/v1/foo"
    And the transport kafka producer has messages below:
      """
      [
        {
          "payload":
            {
              "foo_id":"9cc900eb-663a-4292-876d-5a77eeefade9",
              "name":"Some foo name",
              "created_at": "@datetime@",
              "occurred_on":"@datetime@"
            },
          "metadata":
            {
              "id":"@uuid@",
              "name":"app.context.foo.domain.write.event.foo_was_created"
            }
        }
      ]
      """

  Scenario: It can't create a Foo when invalid request body
    When A POST request is sent to "/v1/foo" with JSON body:
      """
       {
           "name": "Some foo name"
       }
       """
    Then the response header "Content-Type" should be equal to "application/json"
    And the JSON response should be equal to:
        """
        {
            "code": 400,
            "status": "Bad Request",
            "message": "Keyword validation failed: Required property 'id' must be present in the object"
        }
        """
    And the response code should be 400
    And the response should be a documented and validated with OpenApi schema POST "/v1/foo"
    And the transport kafka producer is empty

  Scenario: It can't create a Foo when Foo already exists
    Given I load fixtures for groups "foo"
    When A POST request is sent to "/v1/foo" with JSON body:
      """
       {
           "id": "7f590fc8-1298-4fb7-927e-a38ae50bc705",
           "name": "Some foo name"
       }
       """
    Then the response header "Content-Type" should be equal to "application/json"
    And the JSON response should be equal to:
        """
        {
            "code": 409,
            "status": "Conflict",
            "message": "Foo with id 7f590fc8-1298-4fb7-927e-a38ae50bc705 already exists"
        }
        """
    And the response code should be 409
    And the response should be a documented and validated with OpenApi schema POST "/v1/foo"
    And the transport kafka producer is empty
