<?php

/**
 * job actions.
 *
 * @package    jobeet
 * @subpackage job
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class jobActions extends sfActions {
	public function executeIndex(sfWebRequest $request) {
		$criteria = new Criteria();
		$criteria->add(JobeetJobPeer::EXPIRES_AT, time(), Criteria::GREATER_THAN);
		$this->jobeet_job_list = JobeetJobPeer::doSelect($criteria);
		//$this->getLogger()->log(print_r($this->jobeet_job_list,true));
		$this->jobeet_job_list = JobeetJobPeer::getActiveJobs();
		//$this->getLogger()->log(print_r($this->jobeet_job_list,true));
		$this->categories = JobeetCategoryPeer::getWithJobs();
		//$this->getLogger()->log(print_r($this->categories,true));

	}

	public function executeShow(sfWebRequest $request) {
		//$this->job = JobeetJobPeer::retrieveByPk($request->getParameter('id'));
		//$this->forward404Unless($this->job);
		$this->job = $this->getRoute()->getObject();
		$jobs = $this->getUser()->getAttribute('job_history', array());
		array_unshift($jobs, $this->job->getId());
		//$this->getLogger()->log('----------------------------' . print_r($this->job->getId(),true));
		$this->getUser()->addJobToHistory($this->job);
		$criteria = new Criteria();
		$criteria->add(JobeetJobPeer::ID, $this->job->getId(), Criteria::EQUAL);
		$job = JobeetJobPeer::doSelect($criteria);
		$criteria1 = new Criteria();
		$criteria1->add(JobeetCategoryPeer::ID, $this->job->getCategoryId(), Criteria::EQUAL);
		$category = JobeetCategoryPeer::doSelect($criteria);
		$this->getLogger()->log('444444444444444444444444444' . print_r($category,true));
		//$category = JobeetCategoryPeer::getForSlug();
		//$this->getLogger()->log('444444444444444444444444444' . print_r($this->job,true));
		//$this->job = $jobInfo[0];
		//$this->job = $this->category->getActiveJobs();
		//$this->getLogger()->log('----------------------------' . print_r($this->job,true));
	}

	public function executeNew(sfWebRequest $request) {
		$job = new JobeetJob();
		$job->setType('full-time');
		$this->form = new JobeetJobForm($job);
	}

	public function executeCreate(sfWebRequest $request) {
		$this->form = new JobeetJobForm();
		$this->processForm($request, $this->form);
		$this->setTemplate('new');
	}

	public function executeEdit(sfWebRequest $request) {
		$this->form = new JobeetJobForm($this->getRoute()->getObject());
	}

	public function executeUpdate(sfWebRequest $request) {
		$this->form = new JobeetJobForm($this->getRoute()->getObject());
		$this->processForm($request, $this->form);
		$this->setTemplate('edit');
	}

	public function executeDelete(sfWebRequest $request) {
		$request->checkCSRFProtection();

		$this->forward404Unless($jobeet_job = JobeetJobPeer::retrieveByPk($request->getParameter('id')),sprintf('Object jobeet_job does not exist (%s).',$request->getParameter('id')));
		$jobeet_job->delete();
		$this->redirect('job/index');
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {
		$form->bind($request->getParameter($form->getName()),$request->getFiles($form->getName()));
		if ($form->isValid())
		 {
			$job = $form->save();
			$this->redirect($this->generateUrl('job_show', $job));
		 }
	}
	/**static public function getActiveJobs() {
	    $criteria = new Criteria();
	    $criteria->add(self::EXPIRES_AT, time(), Criteria::GREATER_THAN);
	    $criteria->addDescendingOrderByColumn(self::EXPIRES_AT);
	
	    return self::doSelect($criteria);
	}*/
	public function executePublish(sfWebRequest $request) {
		$request->checkCSRFProtection();

		$job = $this->getRoute()->getObject();
		$job->publish();

		$this->getUser()->setFlash('notice',sprintf('Your job is now online for %s days.',sfConfig::get('app_active_days')));

		$this->redirect($this->generateUrl('job_show_user', $job));
	}
	public function executeExtend(sfWebRequest $request) {
		$request->checkCSRFProtection();

		$job = $this->getRoute()->getObject();
		$this->forward404Unless($job->extend());

		$this->getUser()->setFlash('notice',sprintf('Your job validity has been extend until %s.',$job->getExpiresAt('m/d/Y')));

		$this->redirect($this->generateUrl('job_show_user', $job));
	}

}
