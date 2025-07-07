import React, { useState } from 'react'
import { AiOutlineSearch } from 'react-icons/ai'
import { words } from './data'

const Searchbar = () => {
    const [activeSearch, setActiveSearch] = useState([])

    const handleSearch = (e) => {
        if (e.target.value === '') {
            setActiveSearch([])
            return false
        }
        setActiveSearch(words.filter(w => w.includes(e.target.value)).slice(0, 8))
    }

    return (
        <form className="searchbar-form">
            <div className="searchbar-input-wrapper">
                <input
                    type="search"
                    placeholder="Search Property"
                    className="searchbar-input"
                    onChange={handleSearch}
                />
                <button className="searchbar-btn">
                    <AiOutlineSearch />
                </button>
            </div>

            {
                activeSearch.length > 0 && (
                    <div className="searchbar-dropdown">
                        {
                            activeSearch.map(s => (
                                <span key={s}>{s}</span>
                            ))
                        }
                    </div>
                )
            }
        </form>
    )
}

export default Searchbar