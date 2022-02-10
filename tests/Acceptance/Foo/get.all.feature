Feature:
    In order to retrieve all Foo
    As an API user
    I want to test that GET requests return the proper response

    Scenario: It can retrieve all Foo
        Given I load fixtures for groups "foo,bar"
        When A GET request is sent to "/v1/foo"
        Then the response header "Content-Type" should be equal to "application/json"
        And the JSON response should be equal to:
        """
        [
          {
            "id": "1ca06159-6f66-45c6-aa80-1cf5141f66d6",
            "name": "Some Foo name 2",
            "bar": [
              {
                "id": "06b433af-5699-4cf2-8fb0-29cca9e694c3",
                "name": "Some Bar name 3"
              }
            ]
          },
          {
            "id": "6b7dde86-52c3-45d2-a623-f6bc6f142e29",
            "name": "Some Foo name 5",
            "bar": []
          },
          {
            "id": "782416f0-5d50-4478-821a-48e5d1f0391d",
            "name": "Some Foo name 3",
            "bar": []
          },
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
            ]
          },
          {
            "id": "a557c2ab-b48b-4a02-acda-570d3de4b154",
            "name": "Some Foo name 4",
            "bar": [
              {
                "id": "5fef1065-8fe3-4e29-8712-5eb89fdbc0a0",
                "name": "Some Bar name 4"
              }
            ]
          }
        ]
        """
        And the response code should be 200
        And the response should be a documented and validated with OpenApi schema GET "/v1/foo"

    Scenario: It can retrieve all Foo with pagination params
        Given I load fixtures for groups "foo"
        When A GET request is sent to "/v1/foo?limit=2&offset=1"
        Then the response header "Content-Type" should be equal to "application/json"
        And the JSON response should be equal to:
        """
        [
            {
                "id": "6b7dde86-52c3-45d2-a623-f6bc6f142e29",
                "name": "Some Foo name 5",
                "bar": []
            },
            {
                "id": "782416f0-5d50-4478-821a-48e5d1f0391d",
                "name": "Some Foo name 3",
                "bar": []
            }
        ]
        """
        And the response code should be 200
        And the response should be a documented and validated with OpenApi schema GET "/v1/foo?limit=2&offset=1"

    Scenario: It retrieve a missing Foo
        When A GET request is sent to "/v1/foo"
        Then the response header "Content-Type" should be equal to "application/json"
        And the JSON response should be equal to:
        """
        []
        """
        And the response code should be 200
        And the response should be a documented and validated with OpenApi schema GET "/v1/foo"
