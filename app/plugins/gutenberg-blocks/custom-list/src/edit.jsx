/**
 * WordPress Block Editor Components.
 */
import {
    InspectorControls,
    MediaUpload,
    URLInput,
    useBlockProps,
} from '@wordpress/block-editor';

import {
    __experimentalInputControl as InputControl,
    __experimentalBoxControl as BoxControl,
    __experimentalDivider as Divider,
    __experimentalText as Text,
    CheckboxControl,
    ToggleControl,
    Button,
    PanelBody,
    RangeControl,
    ColorPicker, FlexBlock,

} from '@wordpress/components';
import {v4 as uid} from 'uuid';


/**
 * Edit function for the block.
 *
 * @param {Object} props               Properties passed to the function.
 * @param {Object} props.attributes    Available block attributes.
 * @param {Function} props.setAttributes Function to update block attributes.
 */
export default function Edit({attributes, setAttributes}) {
    const {
        paddings,
        margins,
        positions,
        listItems,
        iconSizes,
        itemsGap,
        divider,
        dividerColor,
        textColor,
        backgroundColor,
        backgroundColorFullWidth
    } = attributes; // Destructure attributes with default value


    const componentId = `id_${uid().split('-')[0]}`


    // Add a new item to the list
    const toCreateItem = () => {
        const newItem = {
            id: uid(),
            media: undefined,
            isLink: false,
            text: '',
            url: undefined,
        };

        // Update attributes with new item
        const updatedList = [...listItems, newItem];
        setAttributes({listItems: updatedList})
    };

    const toRemoveItem = (id) => {
        const updatedList = listItems.filter(item => item.id !== id);
        setAttributes({listItems: updatedList})
    }

    // Update an item by id and property
    const updateItem = (id, property, value) => {
        const updatedList = listItems.map((item) =>
            item.id === id ? {...item, [property]: value} : item
        );
        setAttributes({listItems: updatedList})
    };


    const toAddPX = (object) => {
        return Object.fromEntries(
            Object.entries(object).map(([key, value]) => [
                key,
                value === undefined ? "0px" : value.endsWith("px") ? value : `${value}px`,
            ])
        );
    }
    const beforeStyles = backgroundColorFullWidth === false ? `
        .custom-list--edit[id='${componentId}']::before {
            content: ' ';
            position: absolute;
            background-color: ${backgroundColor};
            width: 100%;
            height: 100%;
            z-index: -1;
            left:0;
            top:0;
        }
    `: `
        .custom-list--edit[id='${componentId}']::before {
            content: ' ';
            position: absolute;
            background-color: ${backgroundColor};
            width: 100vw;
            left: 50%;
            margin-left: -50vw;
            top:0;
            height: 100%;
            z-index: -1;
        }
    `;


    return (

        <>
            <style>{beforeStyles}</style>

            <div {...useBlockProps()} className="custom-list--edit" id={componentId}
                 style={{
                     padding: `${paddings.top} ${paddings.right} ${paddings.bottom} ${paddings.left}`,
                     margin: `${margins.top} ${margins.right} ${margins.bottom} ${margins.left}`,
                 }}
            >

                <InspectorControls>
                    <PanelBody title="General options" initialOpen={true}>
                        <RangeControl
                            __nextHasNoMarginBottom
                            label="Gap between items"
                            value={itemsGap}
                            onChange={(value) => setAttributes({itemsGap: value})}
                            min={1}
                            max={100}
                        />

                        <Text>Background color</Text>
                        <ColorPicker
                            color={backgroundColor}
                            onChange={(value) => setAttributes({backgroundColor: value})}
                            enableAlpha
                            defaultValue="#fff"
                        />

                        <Text>Text color</Text>
                        <ColorPicker
                            color={textColor}
                            onChange={(value) => setAttributes({textColor: value})}
                            enableAlpha
                            defaultValue="#000"
                        />

                        <CheckboxControl
                            __nextHasNoMarginBottom
                            label="Apply background color for whole screen width"
                            checked={backgroundColorFullWidth}
                            onChange={isChecked => {
                                setAttributes({backgroundColorFullWidth: isChecked})
                            }}
                        />

                    </PanelBody>

                    <PanelBody title="Icons" initialOpen={true}>
                        <RangeControl
                            __nextHasNoMarginBottom
                            label="Size"
                            value={iconSizes}
                            onChange={(value) => setAttributes({iconSizes: value})}
                            min={1}
                            max={100}
                        />
                    </PanelBody>

                    <PanelBody title="Padding & Margin" initialOpen={true}>

                        <BoxControl
                            label={'Padding'}
                            __next40pxDefaultSize={true}
                            values={paddings}
                            onChange={(value) => setAttributes({paddings: toAddPX(value)})}
                        />

                        <BoxControl
                            label={'Margin'}
                            __next40pxDefaultSize={true}
                            values={margins}
                            onChange={(value) => setAttributes({margins: toAddPX(value)})}
                        />


                    </PanelBody>
                    <PanelBody title="Dirrection" initialOpen={true}>
                        <CheckboxControl
                            __nextHasNoMarginBottom
                            label="Is horizontal"
                            checked={positions === "horizontal"}
                            onChange={(isChecked) => {
                                if (isChecked) {
                                    setAttributes({positions: "horizontal"})
                                } else {
                                    setAttributes({positions: "vertical"})
                                }

                            }}
                        />

                        <CheckboxControl
                            __nextHasNoMarginBottom
                            label="Is vertical"
                            checked={positions === "vertical"}
                            onChange={(isChecked) => {
                                if (isChecked) {
                                    setAttributes({positions: "vertical"})
                                } else {
                                    setAttributes({positions: "horizontal"})
                                }

                            }}
                        />



                        {positions === 'horizontal' ?

                            <CheckboxControl
                                __nextHasNoMarginBottom
                                label="Add divider"
                                checked={divider}
                                onChange={(isChecked) => { setAttributes({divider: isChecked})}}
                            />


                            : null}



                        {divider ? <>
                            <Text>Divider color</Text>
                            <FlexBlock style={{
                                position: 'relative',
                                height: '70px',
                                width: '70px',
                                marginBottom: '20px',
                                backgroundColor : `${backgroundColor}`
                            }}>
                                <Divider className={'custom-list--edit-divider'} orientation={"vertical"} style={{
                                    backgroundColor : `${dividerColor}`
                                }}/>

                            </FlexBlock>


                            <ColorPicker
                                color={dividerColor}
                                onChange={(value) => setAttributes({dividerColor: value})}
                                enableAlpha
                                defaultValue="#000"
                            />

                        </> : null}

                    </PanelBody>


                </InspectorControls>


                <ul className="list-items" style={{gap: itemsGap, color: textColor}}>

                    {listItems.map((item) => {

                        const id = item.id;

                        return (
                            <li key={id} className="list-item">
                                {/* Media Upload */}
                                {item.media ? (
                                    <img style={{maxWidth: `${iconSizes}px`, maxHeight: `${iconSizes}px`}}
                                         src={item.media.sizes?.thumbnail?.url || item.media.url} alt=""/>
                                ) : null}

                                <MediaUpload
                                    onSelect={(media) => updateItem(id, 'media', media)}
                                    value={item.media?.id}
                                    render={({open}) => (
                                        <Button onClick={open} isPrimary>
                                            {item.media ? 'Change icon' : 'Upload icon'}
                                        </Button>
                                    )}
                                />

                                {/* Toggle for Link */}
                                <ToggleControl
                                    className={"toggle"}
                                    label="Is Link?"
                                    checked={item.isLink}
                                    onChange={(newValue) => updateItem(id, 'isLink', newValue)}
                                />

                                {/* URL Input if Link */}
                                {item.isLink && (
                                    <URLInput
                                        className={'input'}
                                        placeholder="URL"
                                        value={item.url}
                                        onChange={(value) => updateItem(id, 'url', value)}
                                    />
                                )}

                                {/* Text Input */}
                                <InputControl
                                    className={'input'}
                                    placeholder="Text"
                                    value={item.text}
                                    onChange={(value) => updateItem(id, 'text', value)}
                                />

                                <Button variant="primary" className="removeItemBtn" onClick={() => {
                                    toRemoveItem(item.id)
                                }}>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px"
                                         viewBox="0 0 24 24">
                                        <g fill="currentColor">
                                            <path d="M8 11a1 1 0 1 0 0 2h8a1 1 0 1 0 0-2z"></path>
                                            <path fillRule="evenodd"
                                                  d="M23 12c0 6.075-4.925 11-11 11S1 18.075 1 12S5.925 1 12 1s11 4.925 11 11m-2 0a9 9 0 1 1-18 0a9 9 0 0 1 18 0"
                                                  clipRule="evenodd"></path>
                                        </g>
                                    </svg>
                                </Button>
                            </li>
                        );
                    })}
                </ul>

                <Button variant="primary" className="addItemBtn" onClick={toCreateItem}>
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16">
                        <path fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round"
                              strokeWidth={1.5} d="M12.75 7.75h-10m5-5v10"></path>
                    </svg>
                    <span>Add Item</span>
                </Button>

            </div>
        </>


    );
}