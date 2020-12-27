# Video Catalog

![Badge](https://img.shields.io/static/v1?label=PHP&message=7.4&color=777BB4&style=for-the-badge&logo=php&logoColor=777BB4)
![Badge](https://img.shields.io/static/v1?label=Laravel&message=8.x&color=ff2d20&style=for-the-badge&logo=laravel&logoColor=ff2d20)
![Badge](https://img.shields.io/static/v1?label=LICENSE&message=MIT&color=32CD32&style=for-the-badge)

## Project created in [The Fullcycle Develop Course](https://fullcycle.com.br/). This application is responsible for managing the video catalog from CodeFlix, a video stream platform like Netflix, Prime Video and similar.  

### Content Table
* [Features](#features)
* [Prerequisites](#prerequisites)
* [Running the application](#running-the-application)

### Features
- [ ] Category
- [ ] Catalog
- [ ] Genre
- [ ] Cast members
- [ ] Featured Video
- [ ] My Videos
- [ ] Subscription
- [ ] Plan
- [ ] Client
- [ ] User

### Prerequisites

Before you start, you will need to install the following tools:
[Git](https://git-scm.com), [Docker](https://www.docker.com).  
Besides that, we recommend working with a  source-code editor like [VSCode](https://code.visualstudio.com/).

### Running the application

```bash
# Clone this repository
$ git clone <https://github.com/iagoneres/ce-video-catalog> ce-video-catalog

# Access the project folder on terminal/cmd
$ cd ce-video-catalog

# Create the containers with docker
$ docker-compose up

# To access the Laravel Container uses the following instruction.
docker-compose exec app sh

# You can verify the application on the browser through this URL http://localhost:8000
```
