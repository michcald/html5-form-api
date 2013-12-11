<?php

namespace Michael\AppBundle\Event;

class MichaelAuthorEvent
{
	const CREATED = 'michael.app.event.author.create';

	const READ = 'michael.app.event.author.read';

	const LISTED = 'michael.app.event.author.list';

	const UPDATED = 'michael.app.event.author.update';

	const DELETED = 'michael.app.event.author.delete';

	const LINKED_ARTICLE = 'michael.app.event.author.link-article';

	const UNLINKED_ARTICLE = 'michael.app.event.author.unlink-article';
}