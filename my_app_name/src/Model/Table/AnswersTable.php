<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Answers Model
 *
 * @property \App\Model\Table\AssessmentsTable&\Cake\ORM\Association\BelongsTo $Assessments
 * @property \App\Model\Table\QuestionsTable&\Cake\ORM\Association\BelongsTo $Questions
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Students
 *
 * @method \App\Model\Entity\Answer newEmptyEntity()
 * @method \App\Model\Entity\Answer newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Answer> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Answer get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Answer findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Answer patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Answer> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Answer|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Answer saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Answer>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Answer>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Answer>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Answer> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Answer>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Answer>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Answer>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Answer> deleteManyOrFail(iterable $entities, array $options = [])
 */
class AnswersTable extends Table
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

        $this->setTable('answers');
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
        $this->belongsTo('Students', [
            'foreignKey' => 'student_id',
            'className' => 'Users',
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

        $validator
            ->integer('student_id')
            ->notEmptyString('student_id');

        $validator
            ->integer('attempt_number')
            ->notEmptyString('attempt_number');

        $validator
            ->scalar('answer_text')
            ->allowEmptyString('answer_text');

        $validator
            ->integer('ai_score')
            ->allowEmptyString('ai_score');

        $validator
            ->scalar('ai_feedback')
            ->allowEmptyString('ai_feedback');

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
        $rules->add($rules->isUnique(['assessment_id', 'question_id', 'student_id', 'attempt_number']), ['errorField' => 'assessment_id']);
        $rules->add($rules->existsIn(['assessment_id'], 'Assessments'), ['errorField' => 'assessment_id']);
        $rules->add($rules->existsIn(['question_id'], 'Questions'), ['errorField' => 'question_id']);
        $rules->add($rules->existsIn(['student_id'], 'Students'), ['errorField' => 'student_id']);

        return $rules;
    }
}
