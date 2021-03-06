<?php
	namespace App\Entity;

	use App\Game\Element;
	use DaybreakStudios\Utility\DoctrineEntities\EntityInterface;
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Validator\Constraints as Assert;

	/**
	 * @ORM\Entity(readOnly=true)
	 * @ORM\Table(name="monster_resistances")
	 *
	 * Class MonsterResistance
	 *
	 * @package App\Entity
	 */
	class MonsterResistance implements EntityInterface {
		use EntityTrait;

		/**
		 * @ORM\ManyToOne(targetEntity="App\Entity\Monster", inversedBy="resistances")
		 * @ORM\JoinColumn(nullable=false)
		 *
		 * @var Monster
		 */
		private $monster;

		/**
		 * @Assert\NotBlank()
		 * @Assert\Choice(callback={"App\Game\Element", "all"})
		 *
		 * @ORM\Column(type="string", length=32)
		 *
		 * @var string
		 * @see Element
		 */
		private $element;

		/**
		 * @ORM\Column(type="text", nullable=true, name="_condition")
		 *
		 * @var string|null
		 */
		private $condition = null;

		/**
		 * MonsterResistance constructor.
		 *
		 * @param Monster     $monster
		 * @param string      $element
		 */
		public function __construct(Monster $monster, string $element) {
			$this->monster = $monster;
			$this->element = $element;
		}

		/**
		 * @return Monster
		 */
		public function getMonster(): Monster {
			return $this->monster;
		}

		/**
		 * @return string
		 */
		public function getElement(): string {
			return $this->element;
		}

		/**
		 * @return string|null
		 */
		public function getCondition(): ?string {
			return $this->condition;
		}

		/**
		 * @param null|string $condition
		 *
		 * @return $this
		 */
		public function setCondition(?string $condition) {
			$this->condition = $condition;

			return $this;
		}
	}