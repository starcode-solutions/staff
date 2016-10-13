<?php

namespace Starcode\Staff\Action\Client;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Starcode\Staff\Entity\Client;
use Zend\Diactoros\Response\JsonResponse;
use Zend\InputFilter\Factory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class RegistrationAction implements InputFilterAwareInterface
{
    /** @var EntityManager */
    private $entityManager;

    /** @var InputFilterInterface */
    private $inputFilter;

    /**
     * RegistrationAction constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $data = $request->getParsedBody();

        $inputFilter = $this->getInputFilter();
        $inputFilter->setData($data);

        if (!$inputFilter->isValid()) {
            return new JsonResponse([
                'success' => false,
                'messages' => $inputFilter->getMessages(),
            ]);
        }

        $client = new Client();
        $client->setName($inputFilter->getValue('name'));
        $client->setIdentifier($inputFilter->getValue('identifier'));
        $client->setSecret(md5($inputFilter->getValue('secret')));
        $client->setRedirectUri($inputFilter->getValue('redirect_uri'));
        $client->setGrantTypes(Client::GRANT_TYPES);

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'data' => $inputFilter->getValues(),
        ]);
    }

    /**
     * Set input filter
     *
     * @param  InputFilterInterface $inputFilter
     * @return InputFilterAwareInterface
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        $this->inputFilter = $inputFilter;

        return $this;
    }

    /**
     * Retrieve input filter
     *
     * @return InputFilterInterface
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $factory = new Factory();

            $this->inputFilter = $factory->createInputFilter([
                'identifier' => [
                    'name' => 'identifier',
                    'required' => true,
                    'validators' => [
                        [
                            'name' => 'NotEmpty',
                        ],
                    ],
                ],

                'name' => [
                    'name' => 'name',
                    'required' => true,
                    'validators' => [
                        [
                            'name' => 'NotEmpty',
                        ],
                    ],
                ],

                'secret' => [
                    'name' => 'secret',
                    'required' => true,
                    'validators' => [
                        [
                            'name' => 'NotEmpty',
                        ],
                        [
                            'name' => 'StringLength',
                            'options' => [
                                'min' => 6,
                            ],
                        ],
                    ],
                ],

                'redirect_uri' => [
                    'name' => 'redirect_uri',
                    'required' => true,
                    'validators' => [
                        [
                            'name' => 'NotEmpty',
                        ],
                    ],
                ],
            ]);
        }

        return $this->inputFilter;
    }
}