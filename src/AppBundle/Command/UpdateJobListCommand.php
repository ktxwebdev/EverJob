<?php

namespace AppBundle\Command;

use AppBundle\Entity\Job;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Update Job List Command
 * 
 * Get the job list and save/update it to the database 
 */
class UpdateJobListCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('everjob:job:update-job-list')->setDescription('Update Job List');
    }

    protected $webSiteParserService;

    public function __construct($webSiteParserService) {
        $this->webSiteParserService = $webSiteParserService;

        parent::__construct();
    }

    /**
     * Execute
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output) {

        $io = new SymfonyStyle($input, $output);
        $io->title('Update Job List');

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        // Get the job list information
        $jobList = $this->webSiteParserService->getWebSiteInformation();

        $nbJobList = count($jobList);

        $io->note($nbJobList . ' jobs have been found.');

        $io->progressStart($nbJobList);

        $batchSize = 10;
        $i = 0;
        $nbNewJob = 0;
        $nbUpdateJob = 0;
        foreach ($jobList as $jobData) {

            $jobNumber = $jobData['number'];

            // Get the job from the database thanks to his number
            $job = $em->getRepository('AppBundle:Job')->findOneByNumber($jobNumber);

            // If the job doesn't exist, create a new one
            if (is_null($job)) {
                $job = new Job();
                $job->setNumber($jobNumber);
                $nbNewJob++;
            } else {
                // If the job already exist, update it
                $job->setDateUpdate(new \DateTime());
                $nbUpdateJob++;
            }

            $job->setLink($jobData['link']);
            $job->setTitle($jobData['title']);
            $job->setCompany($jobData['company']);

            $em->persist($job);

            if (($i % $batchSize) === 0) {

                $io->progressAdvance($batchSize);

                $em->flush();
                $em->clear();
            }

            $i++;
        }

        $em->flush();

        $io->progressFinish();

        if ($nbNewJob != 0) {
            $io->success($nbNewJob . ' new job(s) have been added to the database');
        }

        if ($nbUpdateJob != 0) {
            $io->note($nbUpdateJob . ' job(s) have been updated');
        }
    }

}
