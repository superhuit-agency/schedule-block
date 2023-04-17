# Schedule block

Add scheduling options to WordPress blocks (Gutenberg)

## Usage

Register the blocks you want to have the scheduling options available by adding block(s) through the javascript filter `scheduleBlock.blocksToAddSchedule` which should be enqueued in the editor.

### Example

```javascript
wp.hooks.addFilter('scheduleBlock.blocksToAddSchedule', 'my-plugin/namespace', function( blockNames ) {

	blockNames.push('core/heading');
	blockNames.push('core/image');
	// blockNames.push('my-plugin/block-name');

  return blockNames;
});
```
