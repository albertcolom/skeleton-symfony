Feature:
    In order to retrieve a Foo
    As an API user
    I want to test that GET requests return the proper response

    Scenario: It can retrieve a Foo
        Given I load fixtures for groups "foo,bar"
        And I index foo search
        And I wait to index 5 foo data
        When A GET request is sent to "/v1/foo/7f590fc8-1298-4fb7-927e-a38ae50bc705"
        Then the response header "Content-Type" should be equal to "application/json"
        And the JSON response should be equal to:
        """
        {
          "id": "7f590fc8-1298-4fb7-927e-a38ae50bc705",
          "name": "Some Foo name 1",
          "bar": [
            {
              "id": "d7b651e9-3bc9-4062-a60b-9882fca29b7f",
              "name": "Some Bar name 2"
            },
            {
              "id": "e4b8fdc9-ded0-4c2f-8c3c-f047e3636655",
              "name": "Some Bar name 1"
            }
          ],
          "created_at":"2018-01-18 11:11:11"
        }
        """
        And the response code should be 200
        And the response should be a documented and validated with OpenApi schema GET "/v1/foo/7f590fc8-1298-4fb7-927e-a38ae50bc705"

    Scenario: It can't retrieve a Foo with invalid ID
        When A GET request is sent to "/v1/foo/111111"
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
        And the response should be a documented and validated with OpenApi schema GET "/v1/foo/111111"

    Scenario: It can't retrieve a missing Foo
        When A GET request is sent to "/v1/foo/0b14e425-5f80-4c29-a3ab-49f5f15ca57d"
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
        And the response should be a documented and validated with OpenApi schema GET "/v1/foo/0b14e425-5f80-4c29-a3ab-49f5f15ca57d"
