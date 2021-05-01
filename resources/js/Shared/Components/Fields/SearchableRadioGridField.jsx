import React, { useState } from 'react'

import RadioGridField from '@/Shared/Components/Fields/RadioGridField'

const FieldHeader = ({ options, query, onChange }) => {
    const handleChange = event => {
        onChange(event.target.value)
    }

    return (
        <div>
            <input
                type="text"
                placeholder="Search..."
                className="block w-full px-8 py-3 rounded-t-md bg-transparent text-white border-0 border-b border-gray-700 focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:ring-inset"
                value={query}
                onChange={handleChange}
            />
        </div>
    )
}

const SearchableRadioGridField = ({ options = [], ...props }) => {
    const [query, setQuery] = useState('')

    const filteredOptions = options.filter(option =>
        option.label.toLowerCase().includes(query.toLowerCase())
    )

    return (
        <RadioGridField
            Header={() => (
                <FieldHeader
                    query={query}
                    onChange={query => setQuery(query)}
                />
            )}
            options={filteredOptions}
            {...props}
        />
    )
}

export default SearchableRadioGridField
