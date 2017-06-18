<?php

namespace AppBundle\Security;

use AppBundle\Form\LoginType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use \Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\Security\Core\User\UserProviderInterface;
use \Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;

class LoginTypeAuthenticator extends AbstractFormLoginAuthenticator
{

    private $formFactory;
    private $em;
    private $router;
    private $passwordEncoder;

    public function __construct(FormFactoryInterface $formFactory, EntityManager $em,
                                RouterInterface $router, UserPasswordEncoder $passwordEncoder)
    {
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
    }

    /*
     * Metoda ta zwraca link do którego przekierowany jest użytkownik jeśli autoryzacja się nie powiedzie
     */
    protected function getLoginUrl()
    {
        return $this->router->generate('security_login');
    }

    /*
     * Metoda ta sprawdza każdy request w naszej apce. Należy ją tak ustawić
     * by potrafiła rozpoznać które żądanie jest żądaniem logowania
     */
    public function getCredentials(Request $request)
    {
        $isLoginSubmit = $request->getPathInfo() == '/login' && $request->isMethod('POST');

        if (!$isLoginSubmit) {
            // nie jest spełniony warunek w którym zachodzi autoryzacja
            return null;
        }

        // wczytanie formularza by później obsłużyć dane z niego
        $form = $this->formFactory->create(LoginType::class);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            // zapisujemy w sesji wartosc ostatnio wpisanego loginu by w momencie
            // gdy źle podamy dane logowania to autouzupełni pole login
            $request->getSession()->set(
                Security::LAST_USERNAME,
                $data['login']
            );
            // jeżeli wszystko przebiega dobrze to metoda zwraca dane z formularza logowania
            return $data;
        }
        return null;
    }

    /*
     * Jeśli z metody getCredentials wyjdą dane inne niż null to przekazywane są one
     * pod nazwą credentials do metody getUser. Jest to 2 krok autoryzacji.
     */
    public function getUser($credentials,UserProviderInterface $userProvider)
    {
        $login = $credentials['login'];

        // jeśli w bazie danych znajdzie użytkownika to zwracamy go w returnie tej metody
        return $this->em->getRepository('AppBundle:User')
            ->findOneBy(array(
               'login' => $login
            ));
    }

    /*
     * Ta metoda jest uruchamiana po tym jak getUser zwróci użytkownika.
     * W metodzie tej sprawdzamy drugi stopień autoryzacji usera.
     * W projekcie jest to hasło więc sprawdzamy czy jest dobre
     */
    public function checkCredentials($credentials,UserInterface $user)
    {
        $password = $credentials['password'];

        // metoda ta zwraca true jeśli hasło się zgadza bądź false jeśli nie
        if ($this->passwordEncoder->isPasswordValid($user, $password)) {
            return true;
        }

        return false;
    }

    /*
     * Ta metoda przekierowuje użytkownika jeśli wejdzie na stronę logowania bez przekierowania
     * z innej strony dokąd ma trafić. W sytuacji gdy trafi na stronę
     * logowania przekierowany z innej strony to automatycznie wróci do niej.
     */
    protected function getDefaultSuccessRedirectUrl()
    {
        return $this->router->generate('main');
    }
}