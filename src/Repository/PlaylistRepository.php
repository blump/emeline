<?php

namespace App\Repository;

use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Playlist>
 */
class PlaylistRepository extends ServiceEntityRepository
{
    /**
     * @var array<string, true>
     */
    private const PLAYLIST_FIELDS = ['name' => true];

    /**
     * @var array<string, array<string, true>>
     */
    private const RELATION_FIELDS = [
        'categories' => ['id' => true, 'name' => true],
    ];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    public function add(Playlist $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(Playlist $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
    
    /**
     * Retourne toutes les playlists triées sur le nom de la playlist
     * @return Playlist[]
     */
    public function findAllOrderByName(string $ordre): array
    {
        $ordre = $this->normalizeSortOrder($ordre);

        return $this->createQueryBuilder('p')
                ->leftjoin('p.formations', 'f')
                ->groupBy('p.id')
                ->orderBy('p.name', $ordre)
                ->getQuery()
                ->getResult();       
    } 

    /**
     * Retourne toutes les playlists triées sur leur nombre de formations.
     * @return Playlist[]
     */
    public function findAllOrderByFormationCount(string $ordre): array
    {
        $ordre = $this->normalizeSortOrder($ordre);

        return $this->createQueryBuilder('p')
                ->leftJoin('p.formations', 'f')
                ->groupBy('p.id')
                ->orderBy('COUNT(f.id)', $ordre)
                ->addOrderBy('p.name', 'ASC')
                ->getQuery()
                ->getResult();
    }
	
    /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @return Playlist[]
     */
    public function findByContainValue(string $champ, string $valeur, string $table = ''): array
    {
        if ($valeur === '') {
            return $this->findAllOrderByName('ASC');
        }    

        if ($table === '') {
            $this->validatePlaylistField($champ);

            return $this->createQueryBuilder('p')
                    ->leftjoin('p.formations', 'f')
                    ->where('p.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->groupBy('p.id')
                    ->orderBy('p.name', 'ASC')
                    ->getQuery()
                    ->getResult();              
        }

        $this->validateRelationField($table, $champ);
        $operator = $this->getSearchOperator($champ);
        $searchValue = $this->getSearchValue($champ, $valeur);

        return $this->createQueryBuilder('p')
                    ->leftjoin('p.formations', 'f')
                    ->leftjoin('f.categories', 'c')
                    ->where('c.'.$champ.' '.$operator.' :valeur')
                    ->setParameter('valeur', $searchValue)
                    ->groupBy('p.id')
                    ->orderBy('p.name', 'ASC')
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

    private function validatePlaylistField(string $champ): void
    {
        if (!isset(self::PLAYLIST_FIELDS[$champ])) {
            throw new \InvalidArgumentException('Champ playlist invalide.');
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
