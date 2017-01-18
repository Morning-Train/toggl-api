# About

PHP class to connect with the Toggl API.

This was coded on an early http://morningtrain.dk

#Installation
It can be installed with composer
```
composer require morningtrain/toggl-api
```

# Dependencies

It depends on guzzlehttp/guzzle ver.6.

Guzzle can be added with the following composer snippet:
(or automatically when installing through composer)

```
{
    "require": {
        "guzzlehttp/guzzle": "^6.0"
    }
}
```

# Examples

For details about the different objects required in the Toggl Api, take a look at their documentation:
https://github.com/toggl/toggl_api_docs

## Toggl API

### Initialization

```
$toggl = new MorningTrain\TogglApi\TogglApi('my-api-token');
```

### Clients

https://github.com/toggl/toggl_api_docs/blob/master/chapters/clients.md

#### Creating a client

```
$toggl->createClient($clientObject);
```

#### Updating a client

```
$toggl->updateClient($clientId, $clientObject);
```

#### Deleting a client

```
$toggl->deleteClient($clientId);
```

#### Get all clients

```
$toggl->getClients();
```

#### Get all projects for a client

```
$toggl->getClientProjects($clientId);
```

#### Get all active projects for a client

```
$toggl->getActiveClientProjects($clientId);
```

#### Get all inactive projects for a client

```
$toggl->getInactiveClientProjects($clientId);
```

#### Get both active and inactive projects for a client

```
$toggl->getAllClientProjects($clientId);
```

#### Get client by id

```
$toggl->getClientById($clientId);
```

### Project users

https://github.com/toggl/toggl_api_docs/blob/master/chapters/project_users.md

#### Create project user

```
$toggl->createProjectUser($projectUserObject);
```

#### Create project users

```
$toggl->createProjectUsers($projectUserObject);
```

#### Update project user

```
$toggl->updateProjectUser($projectUserId, $projectUserObject);
```

#### Update project users

```
$toggl->updateProjectUsers($projectUserIds, $projectUserObject);
```

#### Create project users

```
$toggl->deleteProjectUser($projectUserId);
```

#### Create project users

```
$toggl->deleteProjectUsers($projectUserIds);
```

### Projects
https://github.com/toggl/toggl_api_docs/blob/master/chapters/projects.md

#### Create project

```
$toggl->createProject($projectObject);
```

#### Update project

```
$toggl->updateProject($projectId, $projectObject);
```

#### Delete project

```
$toggl->deleteProject($projectId);
```

#### Delete projects

```
$toggl->deleteProjects($projectIds);
```

#### Get users for project

```
$toggl->getProjectUserRelations($projectId);
```

#### Get project tasks

```
$toggl->getProjectTasks($projectId);
```

#### Get project by ID

```
$toggl->getProject($projectId);
```

## Reports API

### Initialization

```
$toggl = new MorningTrain\TogglApi\TogglReportsApi('my-api-token');
```




