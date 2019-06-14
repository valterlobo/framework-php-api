<?php
namespace helloapp\modules\config;

use Firebase\JWT\JWT;
use helloapp\modules\domain\repository\UserRepository;
use Psr\Log\LoggerInterface;
use Tuupola\Base62;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

class AuthController
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    private $secret;

    private $logService;

    public function __construct(UserRepository $userRepository, $secret, LoggerInterface $logService)
    {
        $this->userRepository = $userRepository;
        $this->secret = $secret;
        $this->logService = $logService;
    }

    public function auth(Request $request, Response $response)
    {
        $input = $request->getParsedBody();

        $user = $this->userRepository->getUserByEmail($input['email']);

        // verify email address.
        if (!$user) {
            return $response->withJson(['error' => true, 'message' => 'These credentials do not match our records: ']);
        }

        // verify password.
        if (!password_verify($input['password'], $user->password)) {
            return $response->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);
        }

        $now = new \DateTime();
        $future = new \DateTime("+10 minutes");
        //$server = $request->getServerParams();
        $jti = (new Base62)->encode(random_bytes(16));
        $payload = [
            "iat" => $now->getTimeStamp(),
            "exp" => $future->getTimeStamp(),
            "jti" => $jti,
            "sub" => 'localhost',
            'id' => $user->id,
            'email' => $user->email,
            'domain_user' => 'xpto_company', // DICA: estas informações podem ser obtidas no BD ( user )
            'role' => 'manager',
        ];
        $this->logService->info('payload: ' . json_encode($payload));
        $token = JWT::encode($payload, 'supersecret123', "HS256");

        return $response->withJson(['X-Token' => $token, 'expires' => $future->getTimeStamp()]);
    }

}
