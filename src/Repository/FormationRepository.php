<?php

namespace App\Repository;

use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Formation>
 */
class FormationRepository extends ServiceEntityRepository
{
    /**
     * @var array<string, true>
     */
    private const FORMATION_FIELDS = ['title' => true, 'publishedAt' => true];

    /**
     * @var array<string, array<string, true>>
     */
    private const RELATION_FIELDS = [
        'playlist' => ['name' => true],
        'categories' => ['id' => true, 'name' => true],
    ];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }

    public function add(Formation $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(Formation $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Retourne toutes les formations triées sur un champ
     * @return Formation[]
     */
    public function findAllOrderBy(string $champ, string $ordre, string $table = ''): array
    {
        $ordre = $this->normalizeSortOrder($ordre);

        if ($table === '') {
            $this->validateFormationField($champ);

            return $this->createQueryBuilder('f')
                    ->orderBy('f.'.$champ, $ordre)
                    ->getQuery()
                    ->getResult();
        }

        $this->validateRelationField($table, $champ);

        return $this->createQueryBuilder('f')
                    ->join('f.'.$table, 't')
                    ->orderBy('t.'.$champ, $ordre)
                    ->getQuery()
                    ->getResult();
    }

    /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @return Formation[]
     */
    public function findByContainValue(string $champ, string $valeur, string $table = ''): array
    {
        if ($valeur === '') {
            return $this->findAll();
        }

        if ($table === '') {
            $this->validateFormationField($champ);

            return $this->createQueryBuilder('f')
                    ->where('f.'.$champ.' LIKE :valeur')
                    ->orderBy('f.publishedAt', 'DESC')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->getQuery()
                    ->getResult();            
        }

        $this->validateRelationField($table, $champ);
        $operator = $this->getSearchOperator($champ);
        $searchValue = $this->getSearchValue($champ, $valeur);

        return $this->createQueryBuilder('f')
                    ->join('f.'.$table, 't')                    
                    ->where('t.'.$champ.' '.$operator.' :valeur')
                    ->orderBy('f.publishedAt', 'DESC')
                    ->setParameter('valeur', $searchValue)
                    ->getQuery()
                    ->getResult();
    }    
    
    /**
     * Retourne les n formations les plus récentes
     * @return Formation[]
     */
    public function findAllLasted(int $nb): array
    {
        return $this->createQueryBuilder('f')
                ->orderBy('f.publishedAt', 'DESC')
                ->setMaxResults($nb)     
                ->getQuery()
                ->getResult();
    }    
    
    /**
     * Retourne la liste des formations d'une playlist
     * @return Formation[]
     */
    public function findAllForOnePlaylist(int $idPlaylist): array
    {
        return $this->createQueryBuilder('f')
                ->join('f.playlist', 'p')
                ->where('p.id=:id')
                ->setParameter('id', $idPlaylist)
                ->orderBy('f.publishedAt', 'ASC')   
                ->getQuery()
                ->getResult();        
    }

    private function normalizeSortOrder(string $ordre): string
    {
        $ordre = strtoupper($ordre);

        if (!in_array($ordre, ['ASC', 'DESC'], true)) {
            throw new \InvalidArgumentException('Ordre de tri invalide.');
        }

        return $ordre;
    }

    private function validateFormationField(string $champ): void
    {
        if (!isset(self::FORMATION_FIELDS[$champ])) {
            throw new \InvalidArgumentException('Champ formation invalide.');
        }
    }

    private function validateRelationField(string $table, string $champ): void
    {
        if (!isset(self::RELATION_FIELDS[$table][$champ])) {
            throw new \InvalidArgumentException('Champ de relation invalide.');
        }
    }

    private function getSearchOperator(string $champ): string
    {
        return $champ === 'id' ? '=' : 'LIKE';
    }

    private function getSearchValue(string $champ, string $valeur): int|string
    {
        return $champ === 'id' ? (int) $valeur : '%'.$valeur.'%';
    }
    
}
