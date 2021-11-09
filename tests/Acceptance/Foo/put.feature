Feature:
  In order to update a Foo
  As an API user
  I want to test that Foo can be updated

  Scenario: It can update a Foo when Foo exists
    Given I load fixtures for groups "foo,bar"
    And the queue associated to transport ampqp is empty
    When A PUT request is sent to "/foo/7f590fc8-1298-4fb7-927e-a38ae50bc705" with JSON body:
      """
       {
          "name": "New foo name"
       }
       """
    Then the response header "Content-Type" should be equal to "application/json"
    And the JSON response should be equal to:
      """
       {
          "id": "7f590fc8-1298-4fb7-927e-a38ae50bc705",
          "name": "New foo name",
          "bar": [
            {
              "id":"d7b651e9-3bc9-4062-a60b-9882fca29b7f",
              "name":"Some Bar name 2"
            },
            {
              "id":"e4b8fdc9-ded0-4c2f-8c3c-f047e3636655",
              "name":"Some Bar name 1"
             }
           ]
       }
      """
    And the response code should be 200
    And the response should be a documented and validated with OpenApi schema PUT "/foo/7f590fc8-1298-4fb7-927e-a38ae50bc705"
    And the transport ampqp producer has messages below:
      """
      [
        {
          "payload":
            {
              "foo_id":"7f590fc8-1298-4fb7-927e-a38ae50bc705",
              "name":"New foo name",
              "occurred_on":"XXXX-XX-XX XX:XX:XX"
            },
          "metadata":
            {
              "id":"XXX",
              "name":"app.context.foo.domain.foo_was_updated"
            }
        }
      ]
      """

  Scenario: It can create a Foo when Foo not exists
    Given the queue associated to transport ampqp is empty
    When A PUT request is sent to "/foo/09042e35-592e-4057-9e03-597e234eea53" with JSON body:
      """
       {
          "name": "New foo name"
       }
       """
    Then the response header "Content-Type" should be equal to "application/json"
    And the JSON response should be equal to:
      """
       {
          "id": "09042e35-592e-4057-9e03-597e234eea53",
          "name": "New foo name",
          "bar": []
       }
      """
    And the response code should be 201
    And the response should be a documented and validated with OpenApi schema PUT "/foo/09042e35-592e-4057-9e03-597e234eea53"
    And the transport ampqp producer has messages below:
      """
      [
        {
          "payload":
            {
              "foo_id":"09042e35-592e-4057-9e03-597e234eea53",
              "name":"New foo name",
              "occurred_on":"XXXX-XX-XX XX:XX:XX"
            },
          "metadata":
            {
              "id":"XXX",
              "name":"app.context.foo.domain.foo_was_created"
            }
        }
      ]
      """

  Scenario: It can't update a Foo when invalid request body
    When A PUT request is sent to "/foo/d9a15203-77a3-4a2c-8b1c-0d9074937a78" with JSON body:
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
    And the response should be a documented and validated with OpenApi schema PUT "/foo/d9a15203-77a3-4a2c-8b1c-0d9074937a78"
