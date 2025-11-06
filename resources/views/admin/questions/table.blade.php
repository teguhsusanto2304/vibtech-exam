<x-table 
    :items="$questions" 
    :columns="[
        ['field' => 'question_stem', 'label' => 'Question', 'limit' => 60],
        ['field' => 'explanation', 'label' => 'Explanation', 'limit' => 80],
    ]"
    :actions="[
        'show' => 'admin.question-banks.show',
        'edit' => 'admin.question-banks.edit',
        'delete' => 'admin.question-banks.destroy',
    ]"
/>
