---
title: "Envs"
weight: 3
--- 

In this package, some features and behaviours can be controlled using Envs.

#### Available envs

{{< column "methods-container" >}}

{{< column "method" >}}
[RAW_ERROR](#RAW_ERROR)
{{< /column >}}

{{< column "method" >}}
[ENABLE_DB_LOG](#ENABLE_DB_LOG)
{{< /column >}}

{{< column "method" >}}
[REGISTER_ROUTES](#REGISTER_ROUTES)
{{< /column >}}

{{< /column >}}

##### RAW_ERROR

If set `true`, will render the error in default format. Its value is `false` by default.

##### ENABLE_DB_LOG

If set `true`, will log the queries with their execution time. Its value is `false` by default.

##### REGISTER_ROUTES

If set `false`, will register route files in `routes/` directory except default route files (web.php, console.php, etc).
Its value is `true` by default.
