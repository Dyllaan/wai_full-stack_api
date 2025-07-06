# Web Application Integration Module README

## Author
Louis Figes

## Notes
For all endpoints with string parameters, the case is irrelevant
All response is in json format

## Endpoints

### `developer`
- **Supports**: GET
- **Allowed Parameters**: None
- **Example Request**:
- `/api/developer`
- **Response**:
Responds with my name and student code

### `country`
- **Supports**: GET
- **Allowed Parameters**: None
- **Example Request**:
- `/api/country`
- **Response**:
Responds with an array of country json objects

### `preview`
- **Supports**: GET
- **Allowed Parameters**:
  - `limit`: integer
- **Example Request**:
- `/api/preview?limit=2`
- **Response**:
Responds with an array of content json objects

### `authorandaffiliation`
- **Supports**: GET
- **Allowed Parameters**:
- `country`: string, country's name as is returned fromthe country endpoint. 
- `content`: integer - corresponding content id.
- `search`: string, searches the city, institution, title and author.
- `page`: integer
- **Exclusive Parameters**:
- `country`: string
- `content`: string
- **Example Request**:
- `/api/author-and-affiliation?country=united kingdom&search=bristol&page=2`
- **Response**:
Responds with a json array of rows from the author and affiliation table

### `content`
- **Supports**: GET
- **Allowed Parameters**:
- `page`: int
- `type`: string, type of the content eg: paper or late-breaking work, this is as it appears in the type from the type table. 
- `id`: int, is the content id
- `award`: bool, is the content awarded?
- `search`: string, string searches the contenty title and abstract.
- `country`: string, country's name as is returned from the country endpoint. 
- `count`: bool, returns the amount of content items in the database, only used for the homepage, cannot be used with other attributes
- **Example Request**:
- `/api/content/?type=paper&award=True&search=design`
- **Response**:
Responds with data from the content table that is joined with award and type tables

### `content-types`
- **Supports**: GET
- **Allowed Parameters**: None
- **Example Request**:
- `/api/content-types`
- **Response**:
Responds with an array of type strings as json objects

### `current-user`
- **Supports**: GET, PUT, POST, DELETE
- **Requires Authentication**:
  - GET, PUT, DELETE
- **Allowed Parameters on POST and PUT**:
  - `name`: string
  - `email`: string
  - `password`: string
- **Notes**:
  - GET: Returns the current user from the JWT bearer token
  - POST: Registration
  - PUT: Edit account
  - DELETE: Delete account
- **Example Request**:
- `/api/current-user`
- **Response**:
Responds with current user object and jwt (UNLESS DELETE)

### `favourites`
- **Supports**: GET, POST, DELETE
- **Requires Authentication**:
  - GET, POST, DELETE
- **Allowed Parameters**:
  - `content_id`: integer
- **Example Request**:
- `/api/favourites?content_id=95698`
- **Response**:
Responds with either the details of the favourite for that piece of content or if no parameters are passed, all favourites for that user

### `notes`
- **Supports**: GET, POST, DELETE, PUT
- **Requires Authentication**:
  - GET, POST, DELETE, PUT
- **Allowed Parameters**:
  - GET (exclusive):
    - `note_id`: integer
    - `content_id`: integer
  - POST:
    - `content_id`: integer
    - `text`: string
  - PUT:
    - `note_id`: integer
    - `text`: string
  - DELETE:
    - `note_id`: integer
- **Example Request**:
- `/api/notes`
- **Response**:
- ***GET***:
Responds with all notes and the corresponding content if no parameters are passed
Responds with notes for a specific piece of content if content_id is passed
Responds with a specific note if note_id is passed
- ***POST***:
Responds with the details of the created note if content_id is exists and is valid and if text is valid
- ***PUT***:
Responds with new note details
- ***DELETE***:
Responds with a success message

### `token`
- **Supports**: GET
- **Allowed Parameters**: None
- **Note**: Pass authorization headers for username and password as strings.
- **Example Request**:
- `/api/token`
- **Response**:
Responds with user details and a jwt token that is valid for 30 minutes
