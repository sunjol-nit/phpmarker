<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Assessments Model
 *
 * @property \App\Model\Table\AnswersTable&\Cake\ORM\Association\HasMany $Answers
 * @property \App\Model\Table\AssessmentQuestionsTable&\Cake\ORM\Association\HasMany $AssessmentQuestions
 *
 * @method \App\Model\Entity\Assessment newEmptyEntity()
 * @method \App\Model\Entity\Assessment newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Assessment> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Assessment get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Assessment findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Assessment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Assessment> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Assessment|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Assessment saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Assessment>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Assessment>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Assessment>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Assessment> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Assessment>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Assessment>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Assessment>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Assessment> deleteManyOrFail(iterable $entities, array $options = [])
 */
class AssessmentsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('assessments');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->hasMany('Answers', [
            'foreignKey' => 'assessment_id',
        ]);
        $this->hasMany('AssessmentQuestions', [
            'foreignKey' => 'assessment_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        return $validator;
    }
}
