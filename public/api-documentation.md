# CineHall API Documentation

## Base URL
```
http://your-domain.com/api
```

## Authentication
Most endpoints require authentication using JWT (JSON Web Token). Include the token in the Authorization header:
```
Authorization: Bearer <your_token>
```

## Authentication Endpoints

### Login
```http
POST /api/login
```
**Request Body:**
```json
{
    "email": "user@example.com",
    "password": "password123"
}
```
**Response:**
```json
{
    "access_token": "jwt_token",
    "token_type": "bearer"
}
```

### Register
```http
POST /api/register
```
**Request Body:**
```json
{
    "name": "User Name",
    "email": "user@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

### Logout
```http
POST /api/logout
```
**Headers Required:**
- Authorization: Bearer token

### Refresh Token
```http
POST /api/refresh
```
**Headers Required:**
- Authorization: Bearer token

## Movies Endpoints

### List All Movies
```http
GET /api/movies
```
**Headers Required:**
- Authorization: Bearer token

### Get Single Movie
```http
GET /api/movies/{id}
```
**Headers Required:**
- Authorization: Bearer token

### Create Movie (Admin Only)
```http
POST /api/movies
```
**Headers Required:**
- Authorization: Bearer token
- Role: admin

### Update Movie (Admin Only)
```http
PUT /api/movies/{id}
```
**Headers Required:**
- Authorization: Bearer token
- Role: admin

### Delete Movie (Admin Only)
```http
DELETE /api/movies/{id}
```
**Headers Required:**
- Authorization: Bearer token
- Role: admin

### Get Movies by Hall
```http
GET /api/movies/hall/{hallId}
```
**Headers Required:**
- Authorization: Bearer token

## Hall Management Endpoints

### List All Halls
```http
GET /api/halls
```
**Headers Required:**
- Authorization: Bearer token

### Get Single Hall
```http
GET /api/halls/{id}
```
**Headers Required:**
- Authorization: Bearer token

### Create Hall (Admin Only)
```http
POST /api/halls/add
```
**Headers Required:**
- Authorization: Bearer token
- Role: admin

### Update Hall (Admin Only)
```http
PUT /api/halls/{id}
```
**Headers Required:**
- Authorization: Bearer token
- Role: admin

### Delete Hall (Admin Only)
```http
DELETE /api/halls/{id}
```
**Headers Required:**
- Authorization: Bearer token
- Role: admin

### Get Available Seats
```http
GET /api/halls/{id}/available-seats
```
**Headers Required:**
- Authorization: Bearer token

### Get Reserved Seats
```http
GET /api/halls/{id}/reserved-seats
```
**Headers Required:**
- Authorization: Bearer token

## Reservation Management Endpoints

### List All Reservations
```http
GET /api/reservations
```
**Headers Required:**
- Authorization: Bearer token

### Get Single Reservation
```http
GET /api/reservations/{id}
```
**Headers Required:**
- Authorization: Bearer token

### Create Reservation
```http
POST /api/reservations/add
```
**Headers Required:**
- Authorization: Bearer token

### Update Reservation
```http
PUT /api/reservations/{id}
```
**Headers Required:**
- Authorization: Bearer token

### Delete Reservation
```http
DELETE /api/reservations/{id}
```
**Headers Required:**
- Authorization: Bearer token

### Get User's Reservations
```http
GET /api/reservations/user/{userId}
```
**Headers Required:**
- Authorization: Bearer token

### Get Reservation Seats
```http
GET /api/reservations/{id}/seats
```
**Headers Required:**
- Authorization: Bearer token

## Session Management Endpoints

### List All Sessions
```http
GET /api/session/all
```
**Headers Required:**
- Authorization: Bearer token

### Create Session (Admin Only)
```http
POST /api/session/add/{movie_id}/{hall_id}
```
**Headers Required:**
- Authorization: Bearer token
- Role: admin

### Update Session (Admin Only)
```http
PUT /api/session/update/{id}
```
**Headers Required:**
- Authorization: Bearer token
- Role: admin

### Delete Session (Admin Only)
```http
DELETE /api/session/delete/{id}
```
**Headers Required:**
- Authorization: Bearer token
- Role: admin

## Admin Endpoints

### Admin Dashboard
```http
GET /api/admin/data
```
**Headers Required:**
- Authorization: Bearer token
- Role: admin

## Error Responses
The API uses standard HTTP response codes:
- 200: Success
- 201: Created
- 400: Bad Request
- 401: Unauthorized
- 403: Forbidden
- 404: Not Found
- 500: Internal Server Error

## Rate Limiting
The API implements rate limiting to prevent abuse. Please contact the administrator for specific rate limit details.

## Support
For any questions or issues, please contact the system administrator. 