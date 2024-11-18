<?php


class Task
{
    protected $id;
    protected $title;
    protected $content;
    protected $status;
    protected $account_created;
    protected $level;

    public function __construct($id, $title, $content, $status, $account_created, $level)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        if ($status == 0 || $status == 1) {
            $this->status = $status;
        }
        $this->account_created = $account_created;
        if ($level == 'low' || $level == 'medium' || $level == 'hard') {
            $this->level = $level;
        }

    }

    public function save(Database $todo_service)
    {
        $todo_service->insert([
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status,
            'account_created' => $this->account_created,
            'level' => $this->level
        ]);
        return true;
    }

    public function update(Database $todo_service)
    {
        $todo_service->update($this->id, [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status,
            'account_created' => $this->account_created,
            'level' => $this->level
        ]);
        return true;
    }

    public function delete(Database $todo_service)
    {
        $todo_service->delete($this->id);
        return true;
    }


}
