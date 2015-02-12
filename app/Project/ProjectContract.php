<?php namespace Deploy\Project;

interface ProjectContract {

    public function __construct(ProjectRepository $repository);

    public function getConfig();
}
