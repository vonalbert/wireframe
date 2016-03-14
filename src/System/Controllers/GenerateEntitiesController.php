<?php

namespace Wireframe\System\Controllers;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\DisconnectedClassMetadataFactory;
use Doctrine\ORM\Tools\EntityGenerator;
use Slim\Http\Response;
use Wireframe\Entity;

/**
 * @author Alberto Avon <alberto.avon@gmail.com>
 */
class GenerateEntitiesController
{
    
    public function __invoke(EntityManager $em, Response $response)
    {
        $cmf = new DisconnectedClassMetadataFactory();
        $cmf->setEntityManager($em);
        $metadatas = $cmf->getAllMetadata();

        if (count($metadatas)) {
            // Create EntityGenerator
            $entityGenerator = new EntityGenerator();
            $entityGenerator->setGenerateAnnotations(true);
            $entityGenerator->setGenerateStubMethods(true);
            $entityGenerator->setUpdateEntityIfExists(true);
            $entityGenerator->setRegenerateEntityIfExists(false);
            $entityGenerator->setClassToExtend(Entity::class);

            // Generating Entities, insert it into the current working directory
            // then allow the user to place where it like
            $entityGenerator->generate($metadatas, getcwd());

            // Outputting information message
            $output = [];
            foreach ($metadatas as $metadata) {
                $output[] = sprintf('Processed entity "<info>%s</info>"', $metadata->name);
            }
            return $response->write(implode('<br/>', $output));
            
        } else {
            return $response->write('No Metadata Classes to process.');
        }
        
    }
    
}
