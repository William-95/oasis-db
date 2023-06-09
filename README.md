# <h1 align=center><a name="0">**OASIS-DB**</a></h1>

<details>
  <summary>Menù</summary>
 <ol>
   <li><a href="#1">About The Project</a></li>
  <li><a href="#2">Built With</a></li>
  <li><a href="#3">Usage</a></li>
  <li><a href="#4">Contact</a></li>
 </ol>
</details>

### <a name="1">About The Project</a>

<br/>
<img src="resources\img\img_json.jpg" width="30%">

<p align=right><a href="#0">back to top</a></p>

---

### <a name="2">Built With</a>

- [PHP](https://www.w3schools.com/php/)
- [MySql](https://www.w3schools.com/MySQL/default.asp)

<p align=right><a href="#0">back to top</a></p>

---

### <a name="3">Usage</a>

1.  Install Composer packages
    ```sh
    composer install
    ```
2.  Install migrations.sql file:
    Run `php artisan migrate`.

3.  Open `.env` and configure the file to allow a connection to the database.

    ```sh
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=oasis-db
    DB_USERNAME=root
    DB_PASSWORD=password
    ```
4. Run `php artisan serve`.
5.  Show all the user in your localhost.

    ```sh
    http://127.0.0.1:8000/api/users
    ```

6.  Show all the dogs in your localhost.

    ```sh
    http://127.0.0.1:8000/api/dogs
    ```
7.  To work with database install `Postman Desktop Agent` and run it.
    Add `Content-Type:application/json` or `Content-Type:multipart/form-data` in Headers.
    In Body select raw.
    
    <br/>
    <br/>
<!-- USERS -->
##### <h4 align=center>COURSE</h4>
***

- **Read User**:
  <br/>
  Send an HTTP `GET` request to the URL:

```sh
<yourdomain>/api/users
```

- **Update User**:
  <br/>

1. Send an HTTP `PUT` request to the URL:

```sh
 <yourdomain>/api/users/<id>
```

2.  In Body section:

```sh
    {
    "name_":":new name",
    "email":":new email",
    "password":":new password",
    "confirm_password":"new password"
    }
```

- **Create User**:
   <br/>

1. Send an HTTP `POST` request to the URL:

    ```sh
    <yourdomain>/api/users
    ```

2. In Body section:
    
    ```sh
    {
    "name":":name",
    "email":":email",
    "password":":password",
    "confirm_password":":confirm_password"
    } 
    ```

- **Delete User**:
    <br/>
1. Send an HTTP `DELETE` request to the URL:
```sh
     <yourdomain>/api/users/<id>
 ```

 <br/>
 <br/>

<!--DOG-->

 ##### <h4 align=center>DOGS</h4>
***

- **Read Dogs**:
  <br/>
  Send an HTTP `GET` request to the URL:

 ```sh
  <yourdomain>/api/dogs
```

- **Read One Dog**:
  <br/>
  Send an HTTP `GET` request to the URL:

 ```sh
  <yourdomain>/api/dog/<id>
```

- **Update Dog**:
  <br/>

1. Send an HTTP `POST` request to the URL:

 ```sh
  <yourdomain>/api/dogs/<id>
```

2. In Body section:

  ```sh
  {
    "name":":name",
    "sex":"sex",
    "race":":race",
    "size":":size",
    "date_birth":"0000-00-00",
    "microchip":"microchip",
    "date_entry":"0000-00-00",
    "img":":add-file",
    "structure":":structure",
    "contacts":"contacts"
  }
  ```

- **Create Dog**:
  <br/>

1. Send an HTTP `POST` request to the URL:

 ```sh
  <yourdomain>/api/dogs
```

2. In Body section:

  ```sh
  {
  "name":":name",
    "sex":"sex",
    "race":":race",
    "size":":size",
    "date_birth":"0000-00-00",
    "microchip":"microchip",
    "date_entry":"0000-00-00",
    "img":":add-file",
    "structure":":structure",
    "contacts":"contacts"
  }
  ```

- **Delete Dog**:
    <br/>

1. Send an HTTP `DELETE` request to the URL:

 ```sh
  <yourdomain>/api/dogs/<id>
```

<br/>
<br/>


<p align=right><a href="#0">back to top</a></p>

---

### <a name="4">Contact</a>

William - verga.william.95@gmail.com

Project Link: [https://github.com/William-95/oasis-db](https://github.com/William-95/oasis-db)

<p align=right><a href="#0">back to top</a></p>