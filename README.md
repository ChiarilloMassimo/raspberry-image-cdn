#Raspberry image cdn upload

Clone repository:

`git clone https://github.com/ChiarilloMassimo/raspberry-image-cdn.git`

Install dependencies:

`composer install`

Run:

`php -S localhost:8080 web/app.php`


If you have an external hd * optional

`ln -s /media/{HD}/cdn/cache/images cache/images`

`ln -s /media/{HD}/cdn/shared/images shared/images`

###Upload image from url:

[http://localhost:8080/upload?url=https://symfony.com/images/v5/conferences/symfony-con-berlin-2016-mascot.png](Upload)

If success return 

```
{file: "/symfony-con-berlin-2016-mascot"}
```

####View image

`http://localhost:8080/{file}`

Example:

[http://localhost:8080/symfony-con-berlin-2016-mascot](View original)

![symfony-con-berlin-2016-mascot](https://cloud.githubusercontent.com/assets/5167596/13763324/abdd05b4-ea45-11e5-9d89-65e4fb6735a2.png)

###Zoom image

####Custom width and height
`http://localhost:8080/w_{width},h_{height}/{file}`

Example:

[http://localhost:8080/w_50,h_50/symfony-con-berlin-2016-mascot](Custom width and height)

![symfony-con-berlin-2016-mascot 1](https://cloud.githubusercontent.com/assets/5167596/13763364/e32232b0-ea45-11e5-91af-8b7c08767844.png)

####Custom height
`http://localhost:8080/h_{height}/{file}`

Example:

[http://localhost:8080/h_200/symfony-con-berlin-2016-mascot](Custom width and height)

![symfony-con-berlin-2016-mascot 2](https://cloud.githubusercontent.com/assets/5167596/13763453/9fa12c2a-ea46-11e5-9d65-2d87fda8049f.png)


####Custom width
`http://localhost:8080/w_{width}/{file}`

Example:

[http://localhost:8080/w_200/symfony-con-berlin-2016-mascot](Custom width and height)

![symfony-con-berlin-2016-mascot 3](https://cloud.githubusercontent.com/assets/5167596/13763497/e0b8c1c8-ea46-11e5-9fc8-df79acbcb7da.png)

---------------------------

### Do not use in production
There are no tests, and the code is written in a short time
