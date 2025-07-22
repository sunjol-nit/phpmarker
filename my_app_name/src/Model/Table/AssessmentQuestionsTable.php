<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AssessmentQuestions Model
 *
 * @property \App\Model\Table\AssessmentsTable&\Cake\ORM\Association\BelongsTo $Assessments
 * @property \App\Model\Table\QuestionsTable&\Cake\ORM\Association\BelongsTo $Questions
 *
 * @method \App\Model\Entity\AssessmentQuestion newEmptyEntity()
 * @method \App\Model\Entity\AssessmentQuestion newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\AssessmentQuestion> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AssessmentQuestion get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\AssessmentQuestion findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\AssessmentQuestion patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\AssessmentQuestion> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\AssessmentQuestion|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\AssessmentQuestion saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\AssessmentQuestion>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\AssessmentQuestion>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\AssessmentQuestion>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\AssessmentQuestion> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\AssessmentQuestion>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\AssessmentQuestion>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\AssessmentQuestion>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\AssessmentQuestion> deleteManyOrFail(iterable $entities, array $options = [])
 */
class AssessmentQuestionsTable extends Table
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

        $this->setTable('assessment_questions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Assessments', [
            'foreignKey' => 'assessment_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Questions', [
            'foreignKey' => 'question_id',
            'joinType' => 'INNER',
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
            ->integer('assessment_id')
            ->notEmptyString('assessment_id');

        $validator
            ->integer('question_id')
            ->notEmptyString('question_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['assessment_id', 'question_id']), ['errorField' => 'assessment_id']);
        $rules->add($rules->existsIn(['assessment_id'], 'Assessments'), ['errorField' => 'assessment_id']);
        $rules->add($rules->existsIn(['question_id'], 'Questions'), ['errorField' => 'question_id']);

        return $rules;
    }
}
