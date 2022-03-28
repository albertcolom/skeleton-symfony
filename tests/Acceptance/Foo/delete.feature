Feature:
  In order to delete a Foo
  As an API user
  I want to test that Foo can be deleted

  Scenario: It can delete a Foo
    Given I load fixtures for groups "foo,bar"
    And the queue associated to transport ampqp is empty
    When A DELETE request is sent to "/v1/foo/7f590fc8-1298-4fb7-927e-a38ae50bc705"
    Then the response should be empty
    And the response code should be 202
    And the response should be a documented and validated with OpenApi schema DELETE "/v1/foo/7f590fc8-1298-4fb7-927e-a38ae50bc705"
    And the transport ampqp producer has messages below:
      """
      [
        {
          "payload":
            {
              "foo_id":"7f590fc8-1298-4fb7-927e-a38ae50bc705",
              "occurred_on":"DATETIME"
            },
          "metadata":
            {
              "id":"UUID",
              "name":"app.context.foo.domain.event.foo_was_removed"
            }
        }
      ]
      """

  Scenario: It can't delete a Foo with invalid ID
    When A DELETE request is sent to "/v1/foo/111111"
    Then the response header "Content-Type" should be equal to "application/json"
    And the JSON response should be equal to:
        """
        {
            "code":400,
            "status":"Bad Request",
            "message":"Parameter 'fooId' has invalid value '111111'"
        }
        """
    And the response code should be 400
    And the response should be a documented and validated with OpenApi schema DELETE "/v1/foo/111111"

  Scenario: It can't delete a missing Foo
    When A DELETE request is sent to "/v1/foo/0b14e425-5f80-4c29-a3ab-49f5f15ca57d"
    Then the response header "Content-Type" should be equal to "application/json"
    And the JSON response should be equal to:
        """
        {
            "code":404,
            "status":"Not Found",
            "message":"Foo with id 0b14e425-5f80-4c29-a3ab-49f5f15ca57d not found"
        }
        """
    And the response code should be 404
    And the response should be a documented and validated with OpenApi schema DELETE "/v1/foo/0b14e425-5f80-4c29-a3ab-49f5f15ca57d"
