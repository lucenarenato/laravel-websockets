# Solução que encontrei para erro.

> I think there is an issue in the package that needs fixing to be able to work. The error occurs when composer dump-autoload runs.

> So I edited the pragmarx\tracker\src\Support\UserAgentParser.php and edit the construct method.

## Here is my code:

```php
public function __construct($basePath, $userAgent = null)
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (!$userAgent && isset($_SERVER['HTTP_USER_AGENT'])) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        }

        $this->parser = Parser::create()->parse($userAgent);

        $this->userAgent = $this->parser->ua;

        $this->operatingSystem = $this->parser->os;

        $this->device = $this->parser->device;

        $this->basePath = $basePath;

        $this->originalUserAgent = $this->parser->originalUserAgent;
    }

    ```

    - https://stackoverflow.com/questions/61553676/argument-1-passed-to-uaparser-parserparse-must-be-of-the-type-string-null-g
    - https://github.com/antonioribeiro/tracker/issues/490