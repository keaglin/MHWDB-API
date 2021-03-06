<?php
	namespace App\Entity;

	use App\Entity\Strings\MonsterStrings;
	use App\Game\Element;
	use App\Game\MonsterSpecies;
	use App\Game\MonsterType;
	use App\Localization\TranslatableEntityInterface;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Criteria;
	use Doctrine\Common\Collections\Selectable;
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Validator\Constraints as Assert;

	/**
	 * @ORM\Entity(repositoryClass="App\Repository\MonsterRepository")
	 * @ORM\Table(name="monsters")
	 *
	 * Class Monster
	 *
	 * @package App\Entity
	 */
	class Monster implements EntityInterface, TranslatableEntityInterface {
		use EntityTrait;

		/**
		 * @Assert\NotBlank()
		 * @Assert\Choice(callback={"App\Game\MonsterType", "all"})
		 *
		 * @ORM\Column(type="string", length=32)
		 *
		 * @var string
		 * @see MonsterType
		 */
		private $type;

		/**
		 * @Assert\NotBlank()
		 * @Assert\Choice(callback={"App\Game\MonsterSpecies", "all"})
		 *
		 * @ORM\Column(type="string", length=32)
		 *
		 * @var string
		 * @see MonsterSpecies
		 */
		private $species;

		/**
		 * @ORM\ManyToMany(targetEntity="App\Entity\Ailment")
		 * @ORM\JoinTable(name="monster_ailments")
		 *
		 * @var Ailment[]|Collection|Selectable
		 */
		private $ailments;

		/**
		 * @ORM\ManyToMany(targetEntity="App\Entity\Location")
		 * @ORM\JoinTable(name="monster_locations")
		 *
		 * @var Location[]|Collection|Selectable
		 */
		private $locations;

		/**
		 * @Assert\Valid()
		 *
		 * @ORM\OneToMany(
		 *     targetEntity="App\Entity\MonsterResistance",
		 *     mappedBy="monster",
		 *     orphanRemoval=true,
		 *     cascade={"all"}
		 * )
		 *
		 * @var MonsterResistance[]|Collection|Selectable
		 */
		private $resistances;

		/**
		 * @Assert\Valid()
		 *
		 * @ORM\OneToMany(
		 *     targetEntity="App\Entity\MonsterWeakness",
		 *     mappedBy="monster",
		 *     orphanRemoval=true,
		 *     cascade={"all"}
		 * )
		 *
		 * @var MonsterWeakness[]|Collection|Selectable
		 */
		private $weaknesses;

		/**
		 * @Assert\Valid()
		 *
		 * @ORM\OneToMany(
		 *     targetEntity="App\Entity\MonsterReward",
		 *     mappedBy="monster",
		 *     cascade={"all"},
		 *     orphanRemoval=true
		 * )
		 *
		 * @var Collection|Selectable|MonsterReward[]
		 */
		private $rewards;

		/**
		 * @Assert\Valid()
		 *
		 * @ORM\OneToMany(
		 *     targetEntity="App\Entity\Strings\MonsterStrings",
		 *     mappedBy="monster",
		 *     orphanRemoval=true,
		 *     cascade={"all"},
		 *     fetch="EAGER"
		 * )
		 *
		 * @var Collection|Selectable|MonsterStrings[]
		 */
		private $strings;

		/**
		 * @ORM\Column(type="json")
		 *
		 * @var string[]
		 * @see Element::DAMAGE
		 */
		private $elements = [];

		/**
		 * Monster constructor.
		 *
		 * @param string $type
		 * @param string $species
		 */
		public function __construct(string $type, string $species) {
			$this->type = $type;
			$this->species = $species;

			$this->ailments = new ArrayCollection();
			$this->locations = new ArrayCollection();
			$this->resistances = new ArrayCollection();
			$this->weaknesses = new ArrayCollection();
			$this->rewards = new ArrayCollection();
			$this->strings = new ArrayCollection();
		}

		/**
		 * @return string
		 */
		public function getType(): string {
			return $this->type;
		}

		/**
		 * @param string $type
		 *
		 * @return $this
		 */
		public function setType(string $type) {
			$this->type = $type;

			return $this;
		}

		/**
		 * @return string
		 */
		public function getSpecies(): string {
			return $this->species;
		}

		/**
		 * @param string $species
		 *
		 * @return $this
		 */
		public function setSpecies(string $species) {
			$this->species = $species;

			return $this;
		}

		/**
		 * @return string[]
		 */
		public function getElements(): array {
			return $this->elements;
		}

		/**
		 * @param string[] $elements
		 *
		 * @return $this
		 */
		public function setElements(array $elements) {
			$this->elements = $elements;

			return $this;
		}

		/**
		 * @return Ailment[]|Collection|Selectable
		 */
		public function getAilments() {
			return $this->ailments;
		}

		/**
		 * @return Location[]|Collection|Selectable
		 */
		public function getLocations() {
			return $this->locations;
		}

		/**
		 * @return MonsterResistance[]|Collection|Selectable
		 */
		public function getResistances() {
			return $this->resistances;
		}

		/**
		 * @return MonsterWeakness[]|Collection|Selectable
		 */
		public function getWeaknesses() {
			return $this->weaknesses;
		}

		/**
		 * @return MonsterReward[]|Collection|Selectable
		 */
		public function getRewards() {
			return $this->rewards;
		}

		/**
		 * @param Item $item
		 *
		 * @return MonsterReward|null
		 */
		public function getRewardForItem(Item $item): ?MonsterReward {
			$criteria = Criteria::create()
				->where(Criteria::expr()->eq('item', $item))
				->setMaxResults(1);

			$matched = $this->getRewards()->matching($criteria);

			return $matched->count() ? $matched->first() : null;
		}

		/**
		 * @return Collection|Selectable|MonsterStrings[]
		 */
		public function getStrings(): Collection {
			return $this->strings;
		}

		/**
		 * @param string $language
		 *
		 * @return MonsterStrings
		 */
		public function addStrings(string $language): EntityInterface {
			$this->getStrings()->add($strings = new MonsterStrings($this, $language));

			return $strings;
		}
	}