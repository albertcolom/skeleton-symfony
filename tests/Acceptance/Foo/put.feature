Feature:
  In order to update a Foo
  As an API user
  I want to test that Foo can be updated

  Scenario: It can update a Foo when Foo exists
    Given I load fixtures for groups "foo,bar"
    And the queue associated to transport kafka is empty
    When A PUT request is sent to "/v1/foo/7f590fc8-1298-4fb7-927e-a38ae50bc705" with JSON body:
      """
       {
          "name": "New foo name"
       }
       """
    Then the response header "Content-Type" should be equal to "application/json"
    And the response should be empty
    And the response code should be 202
    And the response should be a documented and validated with OpenApi schema PUT "/v1/foo/7f590fc8-1298-4fb7-927e-a38ae50bc705"
    And the transport kafka producer has messages below:
      """
      [
        {
          "payload":
            {
              "foo_id":"7f590fc8-1298-4fb7-927e-a38ae50bc705",
              "name":"New foo name",
              "occurred_on":"DATETIME"
            },
          "metadata":
            {
              "id":"UUID",
              "name":"app.context.foo.domain.write.event.foo_was_updated"
            }
        }
      ]
      """

  Scenario: It can create a Foo when Foo not exists
    Given the queue associated to transport kafka is empty
    When A PUT request is sent to "/v1/foo/09042e35-592e-4057-9e03-597e234eea53" with JSON body:
      """
       {
          "name": "New foo name"
       }
       """
    Then the response header "Content-Type" should be equal to "application/json"
    And the response header "Location" should be equal to "http://localhost/v1/foo/09042e35-592e-4057-9e03-597e234eea53"
    And the response should be empty
    And the response code should be 202
    And the response should be a documented and validated with OpenApi schema PUT "/v1/foo/09042e35-592e-4057-9e03-597e234eea53"
    And the transport kafka producer has messages below:
      """
      [
        {
          "payload":
            {
              "foo_id":"09042e35-592e-4057-9e03-597e234eea53",
              "name":"New foo name",
              "created_at": "DATETIME",
              "occurred_on":"DATETIME"
            },
          "metadata":
            {
              "id":"UUID",
              "name":"app.context.foo.domain.write.event.foo_was_created"
            }
        }
      ]
      """

  Scenario: It can't update a Foo when invalid request body
    When A PUT request is sent to "/v1/foo/d9a15203-77a3-4a2c-8b1c-0d9074937a78" with JSON body:
      """
       {
           "foo_name": "Some foo name"
       }
       """
    Then the response header "Content-Type" should be equal to "application/json"
    And the JSON response should be equal to:
        """
        {
            "code": 400,
            "status": "Bad Request",
            "message": "Keyword validation failed: Required property 'name' must be present in the object"
        }
        """
    And the response code should be 400
    And the response should be a documented and validated with OpenApi schema PUT "/v1/foo/d9a15203-77a3-4a2c-8b1c-0d9074937a78"
