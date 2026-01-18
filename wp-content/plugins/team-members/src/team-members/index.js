import './style.scss';
import Edit from './edit';

import { registerBlockType } from '@wordpress/blocks';

registerBlockType('team/members', {
    edit: Edit,
    save: () => null, // Dynamic block
});
